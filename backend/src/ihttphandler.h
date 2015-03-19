#ifndef IHHTPHANDLER
#define IHHTPHANDLER

#include "qhttpserver/qhttpserverfwd.h"
#include <QJsonObject>
#include <QtSql/QSqlDatabase>
#include "SecretToken.h"

class IHTTPHandler {
	public:
		virtual QString target() = 0;
		virtual QJsonObject api() = 0;
		virtual void handleRequest(QSqlDatabase &db, SecretToken *pToken, QHttpRequest *req, QJsonObject &response) = 0;
};

void setErrorResponse(QJsonObject &response, int code, QString message);

#endif // IHHTPHANDLER
