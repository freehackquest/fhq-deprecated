// TODO 

#ifndef TOKEN_H
#define TOKEN_H

#include <QObject>
#include <QMap>
#include <QJsonObject>
#include <QSettings>
#include <QtSql/QSqlDatabase>
#include "globalcontext.h"
#include "usersession.h"

class GlobalContext
{
		QMap<QString, UserSession*> m_mapUserSessions; // cache user session, todo cleanup inactive user session
    QArray<QString> m_arrFreeDBConnections;
    QArray<QString> m_arrBuzyDBConnections;
    int m_nMaxConnections; // move to settins
    int m_nServerPort;
    QString m_sDatabaseHost;
    QString m_sDatabaseName;
    QString m_sDatabaseUser;
    QString m_sDatabaseUserPassword;
	public:
    GlobalContext(QString configFile);

    DBConnection *getFreeDBConnection(); // must wait if not exists free db // and mutex
    static QString getExampleConfigFile();

    // look in cache and next step look to database
    bool containsUserSession(QString sToken); 

    // return UserSession* if exists
    UserSession* userSession(QString sToken);
};

#endif // TOKEN_H
