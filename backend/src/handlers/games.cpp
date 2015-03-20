#include <QJsonObject>
#include "../ihttphandler.h"
#include "games.h"
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
// ***************** GamesList ****************************************
// ********************************************************************

QString GamesList::target() {
	return "/games/list";
};

// --------------------------------------------------------------------

QJsonObject GamesList::api() {
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

void GamesList::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1014, "token are not found");
		return;
	}
};

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

	QJsonObject obj;
	obj["access"] = QString("authorized");
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
};

// --------------------------------------------------------------------

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
};

// --------------------------------------------------------------------

// ********************************************************************
// ***************** GamesDelete **************************************
// ********************************************************************

QString GamesDelete::target() {
	return "/games/delete";
};

// --------------------------------------------------------------------

QJsonObject GamesDelete::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");

	QJsonObject obj;
	obj["access"] = QString("authorized");
	obj["input"] = parameters;
	obj["path"] = target();
	return obj;
};

// --------------------------------------------------------------------

void GamesDelete::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1025, "token are not found");
		return;
	} else if (!pUserSession->isAdmin()) {
		setErrorResponse(response, 1026, "this method only for admin");
		return;
	}
};

// --------------------------------------------------------------------


// --------------------------------------------------------------------

} // namespace handlers
