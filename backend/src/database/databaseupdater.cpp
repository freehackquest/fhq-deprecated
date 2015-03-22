
#include <QtSql/QSqlDatabase>
#include <QtSql/QSqlError>
#include <QSqlQuery>
#include <QSqlRecord>
#include "databaseupdater.h"
#include <iostream>
#include <QUuid>
#include <QCryptographicHash>
#include <iostream>
#include <string>
#include <termios.h>
#include <unistd.h>

// --------------------------------------------------------------------

void DatabaseUpdater::create(GlobalContext *pGlobalContext) {
    std::cout << "Please your password for root: ";
    
	termios oldt, newt;
	tcgetattr( STDIN_FILENO, &oldt );
	newt = oldt;
	newt.c_lflag &= ~( ICANON | ECHO );
	tcsetattr( STDIN_FILENO, TCSANOW, &newt );
	std::string s;
	getline(std::cin, s);
	tcsetattr( STDIN_FILENO, TCSANOW, &oldt );
	std::cout << "\n";
    
    QSqlDatabase db = QSqlDatabase::addDatabase("QMYSQL", "createdb");
	db.setHostName(pGlobalContext->getDatabaseHost());
    db.setUserName("root");
    db.setPassword(QString(s.c_str()));
    
    if (!db.open()) {
		std::cerr << "[ERROR] " << db.lastError().text().toStdString() << "\n";
		std::cerr << "[ERROR] Failed to connect.\n";
		return;
	} else {
		std::cout << " * Connect to database successfully 'createdb'.\n";
	}
  	
  	// create new database
  	{
		QSqlQuery query(db);
		bool b = query.exec(
			" CREATE DATABASE `" + pGlobalContext->getDatabaseName() + "`"
			" CHARACTER SET utf8 COLLATE utf8_general_ci;"
		);
		std::cout << " * create database " << (b ? "yes" : "no") << " \n";
		if (!b) std::cerr << "[ERROR] " << query.lastError().text().toStdString() << "\n";
	}
	
	// create user
	{
		QSqlQuery query(db);
		bool b = query.exec(
			" CREATE USER '" + pGlobalContext->getDatabaseUserName() + "'@'localhost' "
			" IDENTIFIED BY '" + pGlobalContext->getDatabaseUserPassword() + "'; "
		);
		std::cout << " * create user " << (b ? "yes" : "no") << " \n";
		if (!b) std::cerr << "[ERROR] " << query.lastError().text().toStdString() << "\n";
	}
    
    // grant privileges
    {
		QSqlQuery query(db);
		bool b = query.exec(
			" GRANT ALL PRIVILEGES ON " + pGlobalContext->getDatabaseName() + ".* "
			" TO '" + pGlobalContext->getDatabaseUserName() + "'@'localhost' WITH GRANT OPTION;"
		);
		std::cout << " * grant privileges " << (b ? "yes" : "no") << " \n";
		if (!b) std::cerr << "[ERROR] " << query.lastError().text().toStdString() << "\n";
	}
    
    // flush privileges
    {
		QSqlQuery query(db);
		bool b = query.exec(
			" FLUSH PRIVILEGES; "
		);
		std::cout << " * flush privileges " << (b ? "yes" : "no") << " \n";
		if (!b) std::cerr << "[ERROR] " << query.lastError().text().toStdString() << "\n";
	}
}

// --------------------------------------------------------------------


void DatabaseUpdater::insertUpdate(QSqlDatabase *db, QString name, QString version) {
	QSqlQuery query(*db);
	query.prepare("INSERT INTO backend_dbupdates (name, dt_update, version) VALUES (:name, NOW(), :version)");
	query.bindValue(":name", name);
	query.bindValue(":version", version);
	query.exec();
	std::cout << " * Updated database to version " << version.toStdString() << " (" << name.toStdString() << ")\n";
};

// --------------------------------------------------------------------

QString DatabaseUpdater::getLastUpdate(QSqlDatabase *db) {
	QString version = "u0000";
	QSqlQuery query(*db);
	query.exec("SELECT MAX( version ) as vers FROM backend_dbupdates");
	if (query.next()) {
         version = query.value(0).toString();
    }
    return version;
};

// --------------------------------------------------------------------

bool DatabaseUpdater::update0000(QSqlDatabase *db) {
	QSqlQuery query(*db);
	query.exec(
		" CREATE TABLE IF NOT EXISTS `backend_dbupdates` ( "
		" `id` int(11) NOT NULL AUTO_INCREMENT, "
		" `name` varchar(255) DEFAULT NULL, "
		" `dt_update` datetime DEFAULT NULL,"
		" `version` varchar(255) DEFAULT NULL, "
		" PRIMARY KEY (`id`)"
		" ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
	);
	insertUpdate(db, "new table backend_dbupdates", "u0000");
	return true;
}

// --------------------------------------------------------------------

class Update0001 : IUpdate {
	public:
		virtual QString fromVersion() { return "u0000"; }
		virtual QString toVersion()   { return "u0001"; }
		virtual QString text() { return "new table backend_users"; }			
		virtual bool update(QSqlDatabase *db) {
			QSqlQuery query(*db);
			bool bResult = query.exec(
				" CREATE TABLE IF NOT EXISTS `backend_users` ( "
				" `id` int(11) NOT NULL AUTO_INCREMENT, "
				" `uuid` varchar(255) DEFAULT NULL, "
				" `name` varchar(255) DEFAULT NULL,"
				" `password` varchar(255) DEFAULT NULL, "
				" `role` varchar(255) DEFAULT NULL,"
				" `dt_create` datetime DEFAULT NULL, "
				" `dt_last_logon` datetime DEFAULT NULL, "
				" PRIMARY KEY (`id`)"
				" ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
			);
			if (!bResult) {
				std::cerr << "[ERROR] " << query.lastError().text().toStdString() << "\n";
			}
			return bResult;
		}
};

// --------------------------------------------------------------------

class Update0002 : IUpdate {
	public:
		virtual QString fromVersion() { return "u0001"; }
		virtual QString toVersion()   { return "u0002"; }		
		virtual QString text() { return "added user admin with password admin"; }			
		virtual bool update(QSqlDatabase *db) {
			QSqlQuery query(*db);
			query.prepare("INSERT INTO backend_users (uuid, name, password, role, dt_create, dt_last_logon) VALUES (:uuid, :name, :password, :role, NOW(), NOW())");
			QString uuid = QUuid::createUuid().toString();
			uuid = uuid.mid(1,uuid.length()-2);
			query.bindValue(":uuid", uuid);
			query.bindValue(":name", QString("admin"));
			query.bindValue(":password", QString(QCryptographicHash::hash(QString("admin").toUtf8(),QCryptographicHash::Md5).toHex()));
			query.bindValue(":role", QString("admin"));
			bool bResult = query.exec();
			if (!bResult) {
				std::cerr << "[ERROR] " << query.lastError().text().toStdString() << "\n";
			}
			return bResult;
		}
};

// --------------------------------------------------------------------

class Update0003 : IUpdate {
	public:
		virtual QString fromVersion() { return "u0002"; }
		virtual QString toVersion()   { return "u0003"; }		
		virtual QString text() { return "new table backend_users"; }			
		virtual bool update(QSqlDatabase *db) {
			QSqlQuery query(*db);
			bool bResult = query.exec(
				" CREATE TABLE IF NOT EXISTS `backend_users_tokens` ( "
				" `token` varchar(255) NOT NULL,"
				" `status` varchar(255) NOT NULL, "
				" `data` varchar(4048) NOT NULL, "
				" `dt_start` datetime NOT NULL, "
				" `dt_end` datetime NOT NULL, "
				" PRIMARY KEY (`token`) "
				" ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
			);
			if (!bResult) {
				std::cerr << "[ERROR] " << query.lastError().text().toStdString() << "\n";
			}
			return bResult;
		}
};

// --------------------------------------------------------------------

class Update0004 : IUpdate {
	public:
		virtual QString fromVersion() { return "u0003"; }
		virtual QString toVersion()   { return "u0004"; }		
		virtual QString text() { return "new table backend_teams"; }			
		virtual bool update(QSqlDatabase *db) {
			QSqlQuery query(*db);
			bool bResult = query.exec(
				" CREATE TABLE IF NOT EXISTS `backend_teams` ( "
				" `id` int(11) NOT NULL AUTO_INCREMENT, "
				" `name` varchar(255) NOT NULL, "
				" `logo` varchar(255) NOT NULL, "
				" `description` varchar(4096) NOT NULL, "
				" `ipserver` varchar(255) NOT NULL, "
				" PRIMARY KEY (`id`) "
				" ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
			);
			if (!bResult) {
				std::cerr << "[ERROR] " << query.lastError().text().toStdString() << "\n";
			}
			return bResult;
		}
};

// --------------------------------------------------------------------

class Update0005 : IUpdate {
	public:
		virtual QString fromVersion() { return "u0004"; }
		virtual QString toVersion()   { return "u0005"; }		
		virtual QString text() { return "new table backend_services"; }			
		virtual bool update(QSqlDatabase *db) {
			QSqlQuery query(*db);
			bool bResult = query.exec(
				" CREATE TABLE IF NOT EXISTS `backend_services` ( "
				" `id` int(11) NOT NULL AUTO_INCREMENT, "
				" `gameid` int(11) NOT NULL, "
				" `name` varchar(255) NOT NULL, "
				" `script` varchar(1024) NOT NULL, "
				" PRIMARY KEY (`id`) "
				" ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
			);
			if (!bResult) {
				std::cerr << "[ERROR] " << query.lastError().text().toStdString() << "\n";
			}
			return bResult;
		}
};

// --------------------------------------------------------------------

class Update0006 : IUpdate {
	public:
		virtual QString fromVersion() { return "u0005"; }
		virtual QString toVersion()   { return "u0006"; }		
		virtual QString text() { return "new table backend_games"; }			
		virtual bool update(QSqlDatabase *db) {
			QSqlQuery query(*db);
			bool bResult = query.exec(
				" CREATE TABLE IF NOT EXISTS `backend_games` ( "
				" `id` int(11) NOT NULL AUTO_INCREMENT, "
				" `title` varchar(255) NOT NULL, "
				" `logo` varchar(255) NOT NULL, "		
				" PRIMARY KEY (`id`) "
				" ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
			);
			if (!bResult) {
				std::cerr << "[ERROR] " << query.lastError().text().toStdString() << "\n";
			}
			return bResult;
		}
};

// --------------------------------------------------------------------

class Update0007 : IUpdate {
	public:
		virtual QString fromVersion() { return "u0006"; }
		virtual QString toVersion()   { return "u0007"; }		
		virtual QString text() { return "update backend_services"; }			
		virtual bool update(QSqlDatabase *db) {
			QSqlQuery query(*db);
			bool bResult = query.exec(
				" ALTER TABLE `backend_services` ADD COLUMN `script_body` TEXT NOT NULL; "
			);
			if (!bResult) {
				std::cerr << "[ERROR] " << query.lastError().text().toStdString() << "\n";
			}
			return bResult;
		}
};

// --------------------------------------------------------------------

class Update0008 : IUpdate {
	public:
		virtual QString fromVersion() { return "u0007"; }
		virtual QString toVersion()   { return "u0008"; }		
		virtual QString text() { return "update backend_games"; }			
		virtual bool update(QSqlDatabase *db) {
			QSqlQuery query(*db);
			bool bResult = query.exec(
				" ALTER TABLE `backend_games` ADD COLUMN `name` varchar(255) NOT NULL; "
			);
			if (!bResult) {
				std::cerr << "[ERROR] " << query.lastError().text().toStdString() << "\n";
			}
			return bResult;
		}
};

// --------------------------------------------------------------------

void DatabaseUpdater::update(GlobalContext *pGlobalContext) {
	std::cout << " * Update database\n";
	AutoDatabaseConnection autodb(pGlobalContext);
	if (autodb.isEmpty()) {
		std::cerr << "[ERROR] could not connect to database \n";
		return;
	}

	int nInstalledUpdates = 0;
	if ( !autodb.db()->tables().contains( QLatin1String("backend_dbupdates"))) {
		update0000(autodb.db());
		nInstalledUpdates++;
	};

	QVector<IUpdate *> updates;

	updates.push_back((IUpdate *) new Update0001());
	updates.push_back((IUpdate *) new Update0002());
	updates.push_back((IUpdate *) new Update0003());
	updates.push_back((IUpdate *) new Update0004());
	updates.push_back((IUpdate *) new Update0005());
	updates.push_back((IUpdate *) new Update0006());
	updates.push_back((IUpdate *) new Update0007());
	updates.push_back((IUpdate *) new Update0008());
	
	for (int i = 0; i < updates.size(); i++) {
		IUpdate *upd = updates[i];
		if (getLastUpdate(autodb.db()) == upd->fromVersion()) {
			if (upd->update(autodb.db())) {
				insertUpdate(autodb.db(), upd->text(), upd->toVersion());
				nInstalledUpdates++;
			} else {
				std::cout << " * Problem with " << upd->toVersion().toStdString() << "\n";
			}
		}
	}
	
	std::cout << " *** Installed " << nInstalledUpdates << " updates. Current version: " << getLastUpdate(autodb.db()).toStdString() << "\n";
};

// --------------------------------------------------------------------

