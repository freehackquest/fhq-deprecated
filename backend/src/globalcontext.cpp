#include "qhttpserver/qhttpserver.h"
#include "qhttpserver/qhttprequest.h"
#include "qhttpserver/qhttpresponse.h"
#include "SecretToken.h"
#include <QJsonDocument>
#include <QJsonObject>
#include <iostream>
#include <QMap>
#include <QtSql/QSqlError>
#include <QSqlQuery>
#include <QSqlRecord>

// --------------------------------------------------------------------

AutoDatabaseConnection::AutoDatabaseConnection(GlobalContext *pGlobalContext) {
  m_pGlobalContext = pGlobalContext;
  m_pGlobalContext->getDatabaseConnection(m_db);
};

// --------------------------------------------------------------------

AutoDatabaseConnection::~AutoFreeConnection() {
  if (m_db != NULL)
    m_pGlobalContext->toFree(m_db);
};

// --------------------------------------------------------------------

void AutoDatabaseConnection::setdb(QSqlDatabase *db) {
  m_db = db;
}

// --------------------------------------------------------------------

AutoDatabaseConnection::QSqlDatabase *db() {
  return m_db;
};

// --------------------------------------------------------------------

bool AutoDatabaseConnection::isEmpty() {
  return m_db == NULL;
};

// --------------------------------------------------------------------

GlobalContext::GlobalContext(const QString &configFile) {
  QSettings settings(configFile, QSettings::IniFormat);
  m_sDatabaseHost = settings.value("database/host", "localhost").toString();
  m_sDatabaseName = settings.value("database/dbname", "freehackquest").toString();
  m_sDatabaseUserName = settings.value("database/dbusername", "freehackquest_u").toString();
  m_sDatabaseUserPassword = settings.value("database/dbuserpassword", "freehackquest_u").toString();
  m_nServerPort = settings.value("server/port", 8010).toInt();
  m_nMaxDatabaseConnections = settings.value("server/maxDatabaseConnections", 100).toInt();
};

// --------------------------------------------------------------------

static QString GlobalContext::getExampleConfigFile() {
  QString result =
    "[database]\n"
		"host=localhost\n"
		"port=3306\n"
		"dbname=freehackquest\n\n"
  	"dbusername=freehackquest_u\n"
		"dbuserpassword=freehackquest_u\n\n"
		"[server]\n"
		"port=8010\n"
  	"maxDatabaseConnections=100\n";
  return result;
}

// --------------------------------------------------------------------

static bool GlobalContext::checkConfigFile(const QString &configFile)  {
  // check exists config file
	QFile file(configFile);
	if (!file.exists()) {
		std::cerr << "File: '" << configFile.toStdString() << "' not found. Please look --help \n";
		return false;
	}
  return true;
}

// --------------------------------------------------------------------

int GlobalContext::getServerPort() {
  return m_nServerPort;
}

// --------------------------------------------------------------------

void GlobalContext::getDatabaseConnection(AutoDatabaseConnection &autoDb) {
  QMutexLocker ml(&m_MutexDBConnections);

  int nFree = m_arrFreeDatabaseConnections.size()
  int nBuzy = m_arrBuzyDatabaseConnections.size();

  if (nBuzy >= m_nMaxDatabaseConnections && nFree == 0) {
     // TODO: must wait if not exists free db
    autoDb.setdb(NULL);
    std::cerr << "[ERROR] connections more then " << m_nMaxDatabaseConnections << " and non free connections.";
    return;
  }

  if (nFree > 0) {
    autoDb.setdb(m_arrFreeDatabaseConnections.at(0));
    m_arrFreeDatabaseConnections.pop_front();
    m_arrBuzyDatabaseConnections.push_back(autoDb.db());
  } else {
    QString uuid = QUuid::createUuid().toString();
  	uuid = uuid.mid(1,uuid.length()-2);
    QString connectionnamedb = "web" + uuid;
    autoDb.setdb(new QSqlDatabase(QSqlDatabase::addDatabase("QMYSQL", connectionnamedb)));

  	autoDb.db()->setHostName(m_sDatabaseHost);
  	autoDb.db()->setDatabaseName(m_sDatabaseName);
    autoDb.db()->setUserName(m_sDatabaseUserName);
    autoDb.db()->setPassword(m_sDatabaseUserPassword);

  	if (!autoDb.db()->open()) {
  		std::cerr << "[ERROR] " << autoDb.db()->lastError().text().toStdString() << "\n";
  		std::cerr << "[ERROR] Failed to connect.\n";
      delete autoDb.db();
      autoDb.setdb(NULL);
  		return;
  	} else {
      m_arrBuzyDatabaseConnections.push_back(autoDb.db());
  		std::cout << " * Connect to database successfully " << connectionnamedb.toStdString() << ".\n";
  	}
  }
}

// --------------------------------------------------------------------

void GlobalContext::toFree(QSqlDatabase *db) {
  QMutexLocker ml(&m_MutexDBConnections);
  if (m_arrBuzyDatabaseConnections.contains(db)) {
    int i = m_arrBuzyDatabaseConnections.lastIndexOf(db);
    m_arrBuzyDatabaseConnections.remove(i);
    m_arrFreeDatabaseConnections.push_back(db);
  }
}

// --------------------------------------------------------------------

// return UserSession* if exists
UserSession* userSession(const QUrl& url) {
  
};



bool SecretToken::loadToken(QSqlDatabase &db) {
	QSqlQuery query(db);
	query.prepare("SELECT data FROM backend_users_tokens WHERE token = :token");
	query.bindValue(":token", m_sToken);
	query.exec();
	QSqlRecord rec = query.record();
	if (query.next()) {
		QString data = query.value(rec.indexOf("data")).toString();
		std::cout << data.toStdString() << "\n";
		QJsonDocument doc = QJsonDocument::fromJson(data.toLatin1());
		m_pObj = doc.object();
		return true;
	}
	return false;
};

// --------------------------------------------------------------------

bool SecretToken::saveToken(QSqlDatabase &db) {
	QByteArray body = QJsonDocument(m_pObj).toJson();
	QSqlQuery query(db);
	query.prepare("INSERT INTO backend_users_tokens (token,status,data,dt_start,dt_end) VALUES (?,?,?, NOW(), NOW()) "
	" ON DUPLICATE KEY UPDATE status=?, data=?, dt_end=NOW()");
	query.addBindValue(m_sToken);
	query.addBindValue(QString("active"));
	query.addBindValue(QString(body));
	query.addBindValue(QString("active"));
	query.addBindValue(QString(body));
	return query.exec();
};

// --------------------------------------------------------------------

QJsonObject &SecretToken::getJson() {
	return m_pObj;
}

// --------------------------------------------------------------------

int SecretToken::getUserID() {
	if (m_pObj.contains("userid"))
		return m_pObj.value("userid").toInt();
	return 0;
}

// --------------------------------------------------------------------
