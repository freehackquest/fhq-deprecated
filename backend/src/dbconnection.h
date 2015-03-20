#ifndef TOKEN_H
#define TOKEN_H

#include <QObject>
#include <QMap>
#include <QJsonObject>
#include <QSettings>
#include <QtSql/QSqlDatabase>

class DBConnection
{
    QSqlDatabase *db;
	public:
		DBConnection();
    bool name();
    bool open();
    bool isFree();
};

#endif // TOKEN_H
