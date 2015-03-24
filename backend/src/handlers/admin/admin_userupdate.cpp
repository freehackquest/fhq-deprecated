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

#include "../../qhttpserver/qhttprequest.h"
#include "../../ihttphandler.h"
#include "../admin.h"
#include "../../globalcontext.h"
#include "../../usersession.h"

namespace handlers {

// ********************************************************************
// ***************** AdminUserUpdate **********************************
// ********************************************************************

QString AdminUserUpdate::target() {
	return "/admin/user/update";
};

// --------------------------------------------------------------------

QJsonObject AdminUserUpdate::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");
	parameters["id"] = QString("id of team");

	QJsonObject obj;
	obj["path"] = target();
	obj["method"] = QString("GET");
	obj["access"] = QString("admin");
	obj["input"] = parameters;
	obj["output"] = QString("ok/fail");
	obj["description"] = QString("Update user");
	return obj;
};

// --------------------------------------------------------------------

void AdminUserUpdate::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1074, "token are not found");
		return;
	} else if (!pUserSession->isAdmin()) {
		setErrorResponse(response, 1075, "this method only for admin");
		return;
	}
};

} // namespace handlers
