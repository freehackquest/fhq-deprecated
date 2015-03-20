#ifndef GLOBALCONTEXT_H
#define GLOBALCONTEXT_H

#include <QObject>
#include <QMap>
#include <QJsonObject>
#include <QSettings>
#include <QMutex>
#include <QVector>
#include <QtSql/QSqlDatabase>
#include <QtSql/QSqlError>
#include <QSqlQuery>
#include <QSqlRecord>
#include "globalcontext.h"
#include "usersession.h"

class GlobalContext;

class AutoDatabaseConnection {
  private:
    QSqlDatabase *m_db;
    GlobalContext *m_pGlobalContext;
  public:
    AutoDatabaseConnection(GlobalContext *pGlobalContext);
    ~AutoDatabaseConnection();
    void setdb(QSqlDatabase *);
    QSqlDatabase *db();
    bool isEmpty();
};

class GlobalContext
{
		int m_nMaxDatabaseConnections;
		QVector<QSqlDatabase *> m_arrFreeDatabaseConnections;
		QVector<QSqlDatabase *> m_arrBuzyDatabaseConnections;
		QMutex m_MutexDBConnections;

		// cached user session, todo cleanup inactive user session
		QMap<QString, UserSession*> m_mapUserSessions;
		QMutex m_userSessions;

		int m_nServerPort;
		QString m_sDatabaseHost;
		int m_nDatabasePort;
		QString m_sDatabaseName;
		QString m_sDatabaseUserName;
		QString m_sDatabaseUserPassword;
	public:
		GlobalContext(const QString &configFile);
		static QString getExampleConfigFile();
		static bool checkConfigFile(const QString &configFile);

		// must wait if not exists free db // and mutex
		void getDatabaseConnection(AutoDatabaseConnection &);
		void toFree(QSqlDatabase *);

		// return UserSession* if exists
		UserSession* userSession(const QUrl& url, QSqlDatabase *db);
		void addUserSession(UserSession*, QSqlDatabase *db);
		void removeUserSession(UserSession*, QSqlDatabase *db);

		// getters
		int getServerPort();
		QString getDatabaseHost();
		int getDatabasePort();
		QString getDatabaseName();
		QString getDatabaseUserName();
		QString getDatabaseUserPassword();
};

#endif // TOKEN_H
