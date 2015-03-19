#ifndef TOKEN_H
#define TOKEN_H

#include <QObject>
#include <QMap>
#include <QJsonObject>
#include <QSettings>
#include <QtSql/QSqlDatabase>

class SecretToken
{
		QString m_sToken;
		QJsonObject m_pObj;
	public:
		SecretToken(QString token);
		bool loadToken(QSqlDatabase &db);
		bool saveToken(QSqlDatabase &db);
		int getUserID();
		QJsonObject &getJson();
};

#endif // TOKEN_H
