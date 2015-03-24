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
// ***************** AdminGameStart ***********************************
// ********************************************************************

QString AdminGameStart::target() {
	return "/admin/game/start";
};

// --------------------------------------------------------------------

QJsonObject AdminGameStart::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");
	parameters["name"] = QString("name of team");
	parameters["ipserver"] = QString("ip server of team");

	// TODO:
	// parameters["logo"] = QString("logo");
	// parameters["description"] = QString("description");

	QJsonObject obj;
	obj["path"] = target();
	obj["method"] = QString("GET");
	obj["access"] = QString("admin");
	obj["input"] = parameters;
	obj["output"] = QString("ok/fail");
	return obj;
};

// --------------------------------------------------------------------

void AdminGameStart::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1082, "token are not found");
		return;
	} else if (!pUserSession->isAdmin()) {
		setErrorResponse(response, 1083, "this method only for admin");
		return;
	}
};

// --------------------------------------------------------------------

} // namespace handlers
