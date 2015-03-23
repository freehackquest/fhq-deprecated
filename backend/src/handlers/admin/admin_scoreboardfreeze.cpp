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
	obj["access"] = QString("admin");
	obj["input"] = parameters;
	obj["output"] = QString("result");
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

// ********************************************************************
// ***************** AdminUserInsert **********************************
// ********************************************************************

QString AdminUserInsert::target() {
	return "/admin/user/insert";
};

// --------------------------------------------------------------------

QJsonObject AdminUserInsert::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");
	parameters["name"] = QString("name of user");
	parameters["name"] = QString("role of user (admin or user)");
	parameters["password"] = QString("password of user");

	QJsonObject obj;
	obj["path"] = target();
	obj["access"] = QString("admin");
	obj["input"] = parameters;
	obj["output"] = QString("result");
	return obj;
};

// --------------------------------------------------------------------

void AdminUserInsert::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1066, "token are not found");
		return;
	} else if (!pUserSession->isAdmin()) {
		setErrorResponse(response, 1067, "this method only for admin");
		return;
	}
	
	QUrlQuery urlQuery(req->url());
	QString sName = urlQuery.queryItemValue("name");
	QString sRole = urlQuery.queryItemValue("role");
	QString sPassword = urlQuery.queryItemValue("password");
	
	if (sName.isEmpty()) {
		setErrorResponse(response, 1068, "Parameter name are not found or it is empty");
		return;
	}
	
	if (sPassword.isEmpty()) {
		setErrorResponse(response, 1069, "Parameter password are not found or it is empty");
		return;
	}
	
	if (sRole.isEmpty()) {
		setErrorResponse(response, 1072, "Parameter role are not found or it is empty");
		return;
	}

	if (sRole != "admin" && sRole != "user") {
		setErrorResponse(response, 1073, "Parameter role can be only 'admin' or 'user'");
		return;
	}

	// checked exists user by name
	{
		QSqlQuery query(*db);
		query.prepare("SELECT * FROM backend_users WHERE name = :name");
		query.bindValue(":name", sName);
		query.exec();
		QSqlRecord rec = query.record();

		if (query.next()) {
			setErrorResponse(response, 1070, "The user with this name already exists");
			return;
		}
	}

	QString uuid = QUuid::createUuid().toString();
	uuid = uuid.mid(1,uuid.length()-2);
	
	// insert new user
	{
		QSqlQuery query(*db);
		query.prepare("INSERT INTO backend_users(uuid, name, password, role, dt_create, dt_last_logon) VALUES(:uuid,:name,:password,:role,NOW(),NOW())");
		query.bindValue(":uuid", uuid);
		query.bindValue(":name", sName);
		query.bindValue(":password", QString(QCryptographicHash::hash((sPassword.toUtf8()),QCryptographicHash::Md5).toHex()));
		query.bindValue(":role", sRole);
		if (query.exec()) {
			response["result"] = QString("ok");
			response["id"] = query.lastInsertId().toInt();
		} else {
			setErrorResponse(response, 1071, query.lastError().text());
			return;
		}
	}
};

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
	obj["access"] = QString("admin");
	obj["input"] = parameters;
	obj["output"] = QString("result");
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
