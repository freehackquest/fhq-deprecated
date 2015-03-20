
#include <QtSql/QSqlDatabase>
#include <QtSql/QSqlError>
#include <QSqlQuery>
#include <QSqlRecord>
#include "databaseupdater.h"
#include <iostream>
#include <QUuid>
#include <QCryptographicHash>

// --------------------------------------------------------------------

void DatabaseUpdater::update(QSettings *settings) {
  QSqlDatabase *db = pGlobalContext->getDatabaseConnection();
  if (db == NULL) {
    return;
  }

	int nInstalledUpdates = 0;
	
	if ( !db.tables().contains( QLatin1String("backend_dbupdates"))) {
		update0000(db);
		nInstalledUpdates++;
	};
		
	if (getLastUpdate(db) == "u0000") {
		update0001(db);
		nInstalledUpdates++;
	};
	
	if (getLastUpdate(db) == "u0001") {
		update0002(db);
		nInstalledUpdates++;
	};
	
	if (getLastUpdate(db) == "u0002") {
		update0003(db);
		nInstalledUpdates++;
	};
	
	std::cout << " *** Installed " << nInstalledUpdates << " updates. Current version: " << getLastUpdate(db).toStdString() << "\n";
};

// --------------------------------------------------------------------

void DatabaseUpdater::insertUpdate(QSqlDatabase &db, QString name, QString version) {
	QSqlQuery query(db);
	query.prepare("INSERT INTO backend_dbupdates (name, dt_update, version) VALUES (:name, NOW(), :version)");
	query.bindValue(":name", name);
	query.bindValue(":version", version);
	query.exec();
	std::cout << " * Updated database to version " << version.toStdString() << " (" << name.toStdString() << ")\n";
};

// --------------------------------------------------------------------

QString DatabaseUpdater::getLastUpdate(QSqlDatabase &db) {
	QString version = "u0000";
	QSqlQuery query(db);
	query.exec("SELECT MAX( version ) as vers FROM backend_dbupdates");
	if (query.next()) {
         version = query.value(0).toString();
    }
    return version;
};

// --------------------------------------------------------------------

void DatabaseUpdater::update0000(QSqlDatabase &db) {
	QSqlQuery query(db);
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
}

// --------------------------------------------------------------------

void DatabaseUpdater::update0001(QSqlDatabase &db) {
	QSqlQuery query(db);
	query.exec(
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
	insertUpdate(db, "new table backend_users", "u0001");
}

// --------------------------------------------------------------------

void DatabaseUpdater::update0002(QSqlDatabase &db) {
	QSqlQuery query(db);
	query.prepare("INSERT INTO backend_users (uuid, name, password, role, dt_create, dt_last_logon) VALUES (:uuid, :name, :password, :role, NOW(), NOW())");
	QString uuid = QUuid::createUuid().toString();
	uuid = uuid.mid(1,uuid.length()-2);
	query.bindValue(":uuid", uuid);
	query.bindValue(":name", QString("admin"));
	query.bindValue(":password", QString(QCryptographicHash::hash(QString("admin").toUtf8(),QCryptographicHash::Md5).toHex()));
	query.bindValue(":role", QString("admin"));
	query.exec();
	insertUpdate(db, "added user admin with password admin", "u0002");
}

// --------------------------------------------------------------------

void DatabaseUpdater::update0003(QSqlDatabase &db) {
	QSqlQuery query(db);
	query.exec(
		" CREATE TABLE IF NOT EXISTS `backend_users_tokens` ( "
		" `token` varchar(255) NOT NULL,"
		" `status` varchar(255) NOT NULL, "
		" `data` varchar(4048) NOT NULL, "
		" `dt_start` datetime NOT NULL, "
		" `dt_end` datetime NOT NULL, "
		" PRIMARY KEY (`token`) "
		" ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
	);
	insertUpdate(db, "new table backend_users", "u0003");
}
