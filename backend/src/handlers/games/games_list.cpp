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
// ***************** GamesList ****************************************
// ********************************************************************

QString GamesList::target() {
	return "/games/list";
};

// --------------------------------------------------------------------

QJsonObject GamesList::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");
	parameters["name"] = QString("filter by name");

	QJsonObject obj;
	obj["path"] = target();
	obj["access"] = QString("authorized");
	obj["input"] = parameters;
	obj["output"] = QString("token");
	return obj;
};

// --------------------------------------------------------------------

void GamesList::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1014, "token are not found");
		return;
	}

	// TODO added paging
	QUrlQuery urlQuery(req->url());
	QString name = urlQuery.queryItemValue("name");

	QSqlQuery query(*db);
	query.prepare("SELECT * FROM backend_games WHERE name LIKE :name");
	query.bindValue(":name", "%" + name + "%");
	if (query.exec()) {
		QSqlRecord rec = query.record();
		response["result"] = QString("ok");
		QJsonArray data;
		while (query.next()) {
			QJsonObject jsrec;
			jsrec["id"] = query.value(rec.indexOf("id")).toInt();
			jsrec["name"] = query.value(rec.indexOf("name")).toString();
			data.push_back(jsrec);
		}
		response["data"] = data;
	} else {
		setErrorResponse(response, 1054, query.lastError().text());
		return;
	}
		
};

} // namespace handlers
