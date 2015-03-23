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
// ***************** GamesUpdate **************************************
// ********************************************************************

QString GamesUpdate::target() {
	return "/games/update";
};

// --------------------------------------------------------------------

QJsonObject GamesUpdate::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");

	QJsonObject obj;
	obj["access"] = QString("authorized");
	obj["input"] = parameters;
	obj["path"] = target();
	return obj;
};

// --------------------------------------------------------------------

void GamesUpdate::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1023, "token are not found");
		return;
	} else if (!pUserSession->isAdmin()) {
		setErrorResponse(response, 1024, "this method only for admin");
		return;
	}
	
	QUrlQuery urlQuery(req->url());
	QString sId = urlQuery.queryItemValue("id");
	QString name = urlQuery.queryItemValue("name");
	
	if (sId.isEmpty()) {
		setErrorResponse(response, 1060, "Parameter id are not found or it is empty");
		return;
	}
	
	if (name.isEmpty()) {
		setErrorResponse(response, 1061, "Parameter name are not found or it is empty");
		return;
	}

	bool bConvert;
	int nId = sId.toInt(&bConvert, 10);
	if (!bConvert) {
		setErrorResponse(response, 1062, "Parameter id must be integer");
		return;
	}

	// TODO check exists record with this id
	QSqlQuery query(*db);
	query.prepare("UPDATE backend_games SET name = :name WHERE id = :id");
	query.bindValue(":name", name);
	query.bindValue(":id", nId);
	if (query.exec()) {
		response["result"] = QString("ok");
	} else {
		setErrorResponse(response, 1063, query.lastError().text());
		return;
	}	
};

} // namespace handlers
