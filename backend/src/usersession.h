#ifndef USERSESSION_H
#define USERSESSION_H

#include <QObject>
#include <QMap>
#include <QJsonObject>
#include <QSettings>
#include <QtSql/QSqlDatabase>

class UserSession
{
		QString m_sToken;
		QJsonObject m_pDataToken;
	public:
		UserSession();
		void setToken(QString sToken);
		QString token();
		int userID();
		QJsonObject &json();
};

#endif // USERSESSION_H
