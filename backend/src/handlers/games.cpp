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

#include "../ihttphandler.h"
#include "games.h"
#include "../globalcontext.h"
#include "../usersession.h"
#include "../qhttpserver/qhttprequest.h"

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
	parameters["id"] = QString("id of game");

	QJsonObject obj;
	obj["access"] = QString("admin");
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
	
	QUrlQuery urlQuery(req->url());
	QString sId = urlQuery.queryItemValue("id");

	if (sId.isEmpty()) {
		setErrorResponse(response, 1057, "Parameter id are not found or it is empty");
		return;
	}
	
	bool bConvert;
	int nId = sId.toInt(&bConvert, 10);
	if (!bConvert) {
		setErrorResponse(response, 1058, "Parameter id must be integer");
		return;
	}
	
	// TODO check exists game

	QSqlQuery query(*db);
	query.prepare("DELETE FROM backend_games WHERE id = :id");
	query.bindValue(":id", nId);
	if (query.exec()) {
		response["result"] = QString("ok");
	} else {
		setErrorResponse(response, 1059, query.lastError().text());
		return;
	}
};

// --------------------------------------------------------------------

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
