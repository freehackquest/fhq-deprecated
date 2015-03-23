#include <QJsonObject>
#include <QJsonArray>
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
#include "../games.h"
#include "../../globalcontext.h"
#include "../../usersession.h"
#include "../../qhttpserver/qhttprequest.h"

namespace handlers {

// ********************************************************************
// ***************** GamesUploadLogo **************************************
// ********************************************************************

QString GamesUploadLogo::target() {
	return "/games/uploadlogo";
};

// --------------------------------------------------------------------

QJsonObject GamesUploadLogo::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");
	parameters["id"] = QString("id of game");

	QJsonObject obj;
	obj["access"] = QString("admin");
	obj["input"] = parameters;
	obj["path"] = target();
	return obj;
};

// --------------------------------------------------------------------

void GamesUploadLogo::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1064, "token are not found");
		return;
	} else if (!pUserSession->isAdmin()) {
		setErrorResponse(response, 1065, "this method only for admin");
		return;
	}
	// TODO
};

// --------------------------------------------------------------------

} // namespace handlers
