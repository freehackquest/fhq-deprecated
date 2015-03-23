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
// ***************** AdminUserDelete **********************************
// ********************************************************************

QString AdminUserDelete::target() {
	return "/admin/user/delete";
};

// --------------------------------------------------------------------

QJsonObject AdminUserDelete::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");
	parameters["id"] = QString("id of user");

	QJsonObject obj;
	obj["path"] = target();
	obj["access"] = QString("admin");
	obj["input"] = parameters;
	obj["output"] = QString("result");
	return obj;
};

// --------------------------------------------------------------------

void AdminUserDelete::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1076, "token are not found");
		return;
	} else if (!pUserSession->isAdmin()) {
		setErrorResponse(response, 1077, "this method only for admin");
		return;
	}
	
	QUrlQuery urlQuery(req->url());
	QString sId = urlQuery.queryItemValue("id");

	if (sId.isEmpty()) {
		setErrorResponse(response, 1086, "Parameter id are not found or it is empty");
		return;
	}
	
	bool bConvert;
	int nId = sId.toInt(&bConvert, 10);
	if (!bConvert) {
		setErrorResponse(response, 1087, "Parameter id must be integer");
		return;
	}

	if (pUserSession->userID() == nId) {
		setErrorResponse(response, 1088, "Your could not remove yourself");
		return;
	}

	// checked exists user by name
	{
		QSqlQuery query(*db);
		query.prepare("SELECT * FROM backend_users WHERE id = :id");
		query.bindValue(":id", nId);
		query.exec();
		QSqlRecord rec = query.record();
		if (!query.next()) {
			setErrorResponse(response, 1090, "The user with this id are not exists");
			return;
		}
	}

	{
		QSqlQuery query(*db);
		query.prepare("DELETE FROM backend_users WHERE id = :id");
		query.bindValue(":id", nId);
		if (query.exec()) {
			response["result"] = QString("ok");
		} else {
			setErrorResponse(response, 1089, query.lastError().text());
			return;
		}
	}
};

// --------------------------------------------------------------------

} // namespace handlers
