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
#include <QJsonArray>

#include "../../ihttphandler.h"
#include "../admin.h"
#include "../../globalcontext.h"
#include "../../usersession.h"
#include "../../qhttpserver/qhttprequest.h"

namespace handlers {

// ********************************************************************
// ***************** AdminGameStop **************************************
// ********************************************************************

QString AdminGameStop::target() {
	return "/admin/game/stop";
};

// --------------------------------------------------------------------

QJsonObject AdminGameStop::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");
	parameters["id"] = QString("id of game");

	QJsonObject obj;
	obj["path"] = target();
	obj["access"] = QString("admin");
	obj["input"] = parameters;
	obj["output"] = QString("result");
	return obj;
};

// --------------------------------------------------------------------

void AdminGameStop::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1080, "token are not found");
		return;
	} else if (!pUserSession->isAdmin()) {
		setErrorResponse(response, 1081, "this method only for admin");
		return;
	}
};

// --------------------------------------------------------------------

} // namespace handlers
