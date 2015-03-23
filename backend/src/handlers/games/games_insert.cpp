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
// ***************** GamesInsert **************************************
// ********************************************************************

QString GamesInsert::target() {
	return "/games/insert";
};

// --------------------------------------------------------------------

QJsonObject GamesInsert::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");
	parameters["name"] = QString("name of game");

	QJsonObject obj;
	obj["access"] = QString("admin");
	obj["input"] = parameters;
	obj["path"] = target();
	return obj;
};

// --------------------------------------------------------------------

void GamesInsert::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1021, "token are not found");
		return;
	} else if (!pUserSession->isAdmin()) {
		setErrorResponse(response, 1022, "this method only for admin");
		return;
	}

	QUrlQuery urlQuery(req->url());
	QString name = urlQuery.queryItemValue("name");

	if (name.isEmpty()) {
		setErrorResponse(response, 1055, "Parameter name are not found or it is empty");
		return;
	}

	QSqlQuery query(*db);
	query.prepare("INSERT INTO backend_games(name) VALUES(:name)");
	query.bindValue(":name", name);
	if (query.exec()) {
		response["result"] = QString("ok");
		response["id"] = query.lastInsertId().toInt();
	} else {
		setErrorResponse(response, 1056, query.lastError().text());
		return;
	}	
};

// --------------------------------------------------------------------

} // namespace handlers
