#ifndef IHHTPHANDLER
#define IHHTPHANDLER

#include "qhttpserver/qhttpserverfwd.h"
#include <QJsonObject>
#include <QtSql/QSqlDatabase>
#include "globalcontext.h"

class IHTTPHandler {
	public:
		virtual QString target() = 0;
		virtual QJsonObject api() = 0;
		virtual void handleRequest(GlobalContext *, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) = 0;
};

void setErrorResponse(QJsonObject &response, int code, QString message);

#endif // IHHTPHANDLER
