#ifndef GLOBALCONTEXT_H
#define GLOBALCONTEXT_H

#include <QObject>
#include <QMap>
#include <QJsonObject>
#include <QSettings>
#include <QtSql/QSqlDatabase>
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

    // 
    QMap<QString, UserSession*> m_mapUserSessions; // cached user session, todo cleanup inactive user session


    int m_nServerPort;
    QString m_sDatabaseHost;
    QString m_sDatabaseName;
    QString m_sDatabaseUserName;
    QString m_sDatabaseUserPassword;
	public:
    GlobalContext(QString configFile);
    static QString getExampleConfigFile();
    static bool GlobalContext::checkConfigFile(const QString &configFile);

     // must wait if not exists free db // and mutex
    void getDatabaseConnection(AutoDatabaseConnection &);
    void toFree(QSqlDatabase *);

    // return UserSession* if exists
    UserSession* userSession(const QUrl& url);

    // getters
    int getServerPort();
};

#endif // TOKEN_H
