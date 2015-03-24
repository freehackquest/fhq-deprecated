#include <QJsonObject>
#include <QUuid>
#include <QtSql/QSqlDatabase>
#include <QtSql/QSqlError>
#include <QSqlQuery>
#include <QSqlRecord>
#include <QUrl>
#include <QUrlQuery>
#include <iostream>
#include <QUuid>
#include <QCryptographicHash>

#include "../../ihttphandler.h"
#include "../auth.h"
#include "../../globalcontext.h"
#include "../../usersession.h"
#include "../../qhttpserver/qhttprequest.h"

namespace handlers {

// --------------------------------------------------------------------

QString AuthLogoff::target() {
	return "/auth/logoff";
};

// --------------------------------------------------------------------

QJsonObject AuthLogoff::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");

	QJsonObject obj;
	obj["access"] = QString("authorized");
	obj["input"] = parameters;
	obj["path"] = target();
	return obj;
};

// --------------------------------------------------------------------

void AuthLogoff::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1005, "token are not found");
		return;
	}
	response["result"] = QString("ok");
	pGlobalContext->removeUserSession(pUserSession, db);
};

// --------------------------------------------------------------------

} // namespace handlers
