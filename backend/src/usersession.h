#ifndef TOKEN_H
#define TOKEN_H
// TODO 

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
  	QString getToken();
		bool loadToken();
		bool saveToken();
		int getUserID();
		QJsonObject &getJson();
};

#endif // TOKEN_H
