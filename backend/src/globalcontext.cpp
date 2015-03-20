#include "qhttpserver/qhttpserver.h"
#include "qhttpserver/qhttprequest.h"
#include "qhttpserver/qhttpresponse.h"
#include "globalcontext.h"
#include <QJsonDocument>
#include <QJsonObject>
#include <iostream>
#include <QMap>
#include <QFile>
#include <QUuid>
#include <QUrlQuery>

// --------------------------------------------------------------------

AutoDatabaseConnection::AutoDatabaseConnection(GlobalContext *pGlobalContext) {
  m_pGlobalContext = pGlobalContext;
  m_pGlobalContext->getDatabaseConnection(*this);
};

// --------------------------------------------------------------------

AutoDatabaseConnection::~AutoDatabaseConnection() {
  if (m_db != NULL)
    m_pGlobalContext->toFree(m_db);
};

// --------------------------------------------------------------------

void AutoDatabaseConnection::setdb(QSqlDatabase *db) {
  m_db = db;
}

// --------------------------------------------------------------------

QSqlDatabase * AutoDatabaseConnection::db() {
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
  m_nDatabasePort = settings.value("database/port", 3306).toInt();
  m_sDatabaseUserName = settings.value("database/dbusername", "freehackquest_u").toString();
  m_sDatabaseUserPassword = settings.value("database/dbuserpassword", "freehackquest_u").toString();
  m_nServerPort = settings.value("server/port", 8010).toInt();
  m_nMaxDatabaseConnections = settings.value("server/maxDatabaseConnections", 100).toInt();
};

// --------------------------------------------------------------------

QString GlobalContext::getExampleConfigFile() {
	QString result =
		"[database]\n"
		"host=localhost\n"
		"port=3306\n"
		"dbname=fhqbackend\n\n"
		"dbusername=fhqbackend\n"
		"dbuserpassword=fhqbackend\n\n"
		"[server]\n"
		"port=8010\n"
		"maxDatabaseConnections=100\n";
	return result;
}

// --------------------------------------------------------------------

bool GlobalContext::checkConfigFile(const QString &configFile)  {
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

QString GlobalContext::getDatabaseHost() {
	return m_sDatabaseHost;
}

// --------------------------------------------------------------------

int GlobalContext::getDatabasePort() {
	return m_nDatabasePort;
}

// --------------------------------------------------------------------

QString GlobalContext::getDatabaseName() {
	return m_sDatabaseName;
}

// --------------------------------------------------------------------

QString GlobalContext::getDatabaseUserName() {
	return m_sDatabaseUserName;
}

// --------------------------------------------------------------------

QString GlobalContext::getDatabaseUserPassword() {
	return m_sDatabaseUserPassword;
}

// --------------------------------------------------------------------

void GlobalContext::getDatabaseConnection(AutoDatabaseConnection &autodb) {
  QMutexLocker ml(&m_MutexDBConnections);

  int nFree = m_arrFreeDatabaseConnections.size();
  int nBuzy = m_arrBuzyDatabaseConnections.size();

  if (nBuzy >= m_nMaxDatabaseConnections && nFree == 0) {
     // TODO: must wait if not exists free db
    autodb.setdb(NULL);
    std::cerr << "[ERROR] connections more then " << m_nMaxDatabaseConnections << " and non free connections.";
    return;
  }

  if (nFree > 0) {
    autodb.setdb(m_arrFreeDatabaseConnections.at(0));
    m_arrFreeDatabaseConnections.pop_front();
    m_arrBuzyDatabaseConnections.push_back(autodb.db());
  } else {
    QString uuid = QUuid::createUuid().toString();
  	uuid = uuid.mid(1,uuid.length()-2);
    QString connectionnamedb = "web" + uuid;
    autodb.setdb(new QSqlDatabase(QSqlDatabase::addDatabase("QMYSQL", connectionnamedb)));

  	autodb.db()->setHostName(m_sDatabaseHost);
  	autodb.db()->setDatabaseName(m_sDatabaseName);
    autodb.db()->setUserName(m_sDatabaseUserName);
    autodb.db()->setPassword(m_sDatabaseUserPassword);

  	if (!autodb.db()->open()) {
		std::cerr << "[ERROR] " << autodb.db()->lastError().text().toStdString() << "\n";
		std::cerr << "[ERROR] Failed to connect.\n";
		delete autodb.db();
		autodb.setdb(NULL);
  		return;
  	} else {
		m_arrBuzyDatabaseConnections.push_back(autodb.db());
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
UserSession* GlobalContext::userSession(const QUrl& url, QSqlDatabase *db) {
	QMutexLocker ml(&m_userSessions);
	QUrlQuery urlQuery(url);
	QString sToken = urlQuery.queryItemValue("token");
	
	if (m_mapUserSessions.contains(sToken))
		return m_mapUserSessions.value(sToken);
	
	// try find in database
	QSqlQuery query(*db);
	// TODO check session which ended
	query.prepare("SELECT data FROM backend_users_tokens WHERE token = :token");
	query.bindValue(":token", sToken);
	query.exec();
	QSqlRecord rec = query.record();
	if (query.next()) {
		QString data = query.value(rec.indexOf("data")).toString();
		QJsonDocument doc = QJsonDocument::fromJson(data.toLatin1());
		UserSession* pUserSession = new UserSession();
		pUserSession->json() = doc.object();
		pUserSession->setToken(sToken);
		m_mapUserSessions[sToken] = pUserSession;
		return pUserSession;
	}
	return NULL;
}

// --------------------------------------------------------------------

void GlobalContext::addUserSession(UserSession* pUserSession, QSqlDatabase *db) {
	QMutexLocker ml(&m_userSessions);
	m_mapUserSessions[pUserSession->token()] = pUserSession;
	
	QByteArray body = QJsonDocument(pUserSession->json()).toJson();
	QSqlQuery query(*db);
	query.prepare("INSERT INTO backend_users_tokens (token,status,data,dt_start,dt_end) VALUES (?,?,?, NOW(), NOW()) "
	" ON DUPLICATE KEY UPDATE status=?, data=?, dt_end=NOW()");
	query.addBindValue(pUserSession->token());
	query.addBindValue(QString("active"));
	query.addBindValue(QString(body));
	query.addBindValue(QString("active"));
	query.addBindValue(QString(body));
	query.exec();
}

// --------------------------------------------------------------------

void GlobalContext::removeUserSession(UserSession* pUserSession, QSqlDatabase */*db*/) {
	QMutexLocker ml(&m_userSessions);
	m_mapUserSessions.remove(pUserSession->token());
}

// --------------------------------------------------------------------
