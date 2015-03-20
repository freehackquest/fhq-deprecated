#include <QJsonObject>
#include "../ihttphandler.h"
#include "services.h"
#include "../globalcontext.h"
#include "../usersession.h"
#include <QUuid>
#include <QtSql/QSqlDatabase>
#include <QtSql/QSqlError>
#include <QSqlQuery>
#include <QSqlRecord>
#include <QUrl>
#include <QUrlQuery>
#include "../qhttpserver/qhttprequest.h"
#include <iostream>
#include <QUuid>
#include <QCryptographicHash>

namespace handlers {

// ********************************************************************
// ***************** ServicesList ****************************************
// ********************************************************************

QString ServicesList::target() {
	return "/services/list";
};

// --------------------------------------------------------------------

QJsonObject ServicesList::api() {
	QJsonObject parameters;
	parameters["name"] = QString("your name");
	parameters["password"] = QString("your password");

	QJsonObject obj;
	obj["path"] = target();
	obj["access"] = QString("unauthorized");
	obj["input"] = parameters;
	obj["output"] = QString("token");
	return obj;
};

// --------------------------------------------------------------------

void ServicesList::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1013, "token are not found");
		return;
	}
};

// ********************************************************************
// ***************** ServicesInsert **************************************
// ********************************************************************

QString ServicesInsert::target() {
	return "/services/insert";
};

// --------------------------------------------------------------------

QJsonObject ServicesInsert::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");

	QJsonObject obj;
	obj["access"] = QString("authorized");
	obj["input"] = parameters;
	obj["path"] = target();
	return obj;
};

// --------------------------------------------------------------------

void ServicesInsert::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1015, "token are not found");
		return;
	} else if (!pUserSession->isAdmin()) {
		setErrorResponse(response, 1016, "this method only for admin");
		return;
	}
};

// --------------------------------------------------------------------

// ********************************************************************
// ***************** ServicesUpdate **************************************
// ********************************************************************

QString ServicesUpdate::target() {
	return "/services/update";
};

// --------------------------------------------------------------------

QJsonObject ServicesUpdate::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");

	QJsonObject obj;
	obj["access"] = QString("authorized");
	obj["input"] = parameters;
	obj["path"] = target();
	return obj;
};

// --------------------------------------------------------------------

void ServicesUpdate::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1017, "token are not found");
		return;
	} else if (!pUserSession->isAdmin()) {
		setErrorResponse(response, 1018, "this method only for admin");
		return;
	}
};

// --------------------------------------------------------------------

// ********************************************************************
// ***************** ServicesDelete **************************************
// ********************************************************************

QString ServicesDelete::target() {
	return "/services/delete";
};

// --------------------------------------------------------------------

QJsonObject ServicesDelete::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");

	QJsonObject obj;
	obj["access"] = QString("authorized");
	obj["input"] = parameters;
	obj["path"] = target();
	return obj;
};

// --------------------------------------------------------------------

void ServicesDelete::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1019, "token are not found");
		return;
	} else if (!pUserSession->isAdmin()) {
		setErrorResponse(response, 1020, "this method only for admin");
		return;
	}
};

// --------------------------------------------------------------------

} // namespace handlers
