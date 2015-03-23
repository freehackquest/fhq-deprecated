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
// ***************** AdminUserChangePassword **************************
// ********************************************************************

QString AdminUserChangePassword::target() {
	return "/admin/user/changepassword";
};

// --------------------------------------------------------------------

QJsonObject AdminUserChangePassword::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");

	QJsonObject obj;
	obj["path"] = target();
	obj["access"] = QString("authorized");
	obj["input"] = parameters;
	obj["output"] = QString("list of teams");
	return obj;
};

// --------------------------------------------------------------------

void AdminUserChangePassword::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1084, "token are not found");
		return;
	} else if (!pUserSession->isAdmin()) {
		setErrorResponse(response, 1085, "this method only for admin");
		return;
	}

	QUrlQuery urlQuery(req->url());
	QString sId = urlQuery.queryItemValue("id");
	QString sPassword = urlQuery.queryItemValue("password");
	
	if (sId.isEmpty()) {
		setErrorResponse(response, 1091, "Parameter name are not found or it is empty");
		return;
	}
	
	if (sPassword.isEmpty()) {
		setErrorResponse(response, 1092, "Parameter password are not found or it is empty");
		return;
	}

	bool bConvert;
	int nId = sId.toInt(&bConvert, 10);
	if (!bConvert) {
		setErrorResponse(response, 1093, "Parameter id must be integer");
		return;
	}

	if (pUserSession->userID() == nId) {
		setErrorResponse(response, 1095, "Your could not change password for yourself use this method.");
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
			setErrorResponse(response, 1094, "The user are not exists");
			return;
		}
	}

	{
		QSqlQuery query(*db);
		query.prepare("UPDATE backend_users SET password = :password WHERE id = :id");
		query.bindValue(":password", QString(QCryptographicHash::hash((sPassword.toUtf8()),QCryptographicHash::Md5).toHex()));
		query.bindValue(":id", nId);
		if (query.exec()) {
			response["result"] = QString("ok");
		} else {
			setErrorResponse(response, 1096, query.lastError().text());
			return;
		}
	}
};

} // namespace handlers
