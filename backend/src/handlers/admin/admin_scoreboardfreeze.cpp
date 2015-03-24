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
// ***************** AdminScoreboardFreeze **************************************
// ********************************************************************

QString AdminScoreboardFreeze::target() {
	return "/admin/scoreboard/freeze";
};

// --------------------------------------------------------------------

QJsonObject AdminScoreboardFreeze::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");
	parameters["id"] = QString("id of team");

	QJsonObject obj;
	obj["path"] = target();
	obj["method"] = QString("GET");
	obj["access"] = QString("admin");
	obj["input"] = parameters;
	obj["output"] = QString("ok/fail");
	obj["description"] = QString("Scoreboard freeze (will be not update for users, but will be update for admins)");
	return obj;
};

// --------------------------------------------------------------------

void AdminScoreboardFreeze::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1078, "token are not found");
		return;
	} else if (!pUserSession->isAdmin()) {
		setErrorResponse(response, 1079, "this method only for admin");
		return;
	}
};

// --------------------------------------------------------------------

} // namespace handlers
