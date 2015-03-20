#include <QJsonObject>
#include "../ihttphandler.h"
#include "teams.h"
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
#include <QJsonArray>

namespace handlers {

// ********************************************************************
// ***************** TeamsList ****************************************
// ********************************************************************

QString TeamsList::target() {
	return "/teams/list";
};

// --------------------------------------------------------------------

QJsonObject TeamsList::api() {
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

void TeamsList::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1010, "token are not found");
		return;
	}
	
	// TODO added paging and filters
	/*QUrlQuery urlQuery(req->url());
	QString sId = urlQuery.queryItemValue("id");
	QString name = urlQuery.queryItemValue("name");
	QString ipserver = urlQuery.queryItemValue("ipserver");*/
	
	// TODO check exists record with this id
	QSqlQuery query(*db);
	query.prepare("SELECT * FROM backend_teams");
	// query.bindValue(":name", name);
	if (query.exec()) {
		QSqlRecord rec = query.record();
		response["result"] = QString("ok");
		QJsonArray data;
		while (query.next()) {
			QJsonObject jsrec;
			jsrec["id"] = query.value(rec.indexOf("id")).toInt();
			jsrec["name"] = query.value(rec.indexOf("name")).toInt();
			jsrec["ipserver"] = query.value(rec.indexOf("ipserver")).toInt();
			data.push_back(jsrec);
		}
		response["data"] = data;
	} else {
		setErrorResponse(response, 1037, query.lastError().text());
		return;
	}
		
};

// ********************************************************************
// ***************** TeamsInsert **************************************
// ********************************************************************

QString TeamsInsert::target() {
	return "/teams/insert";
};

// --------------------------------------------------------------------

QJsonObject TeamsInsert::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");
	parameters["name"] = QString("name of team");
	parameters["ipserver"] = QString("ip server of team");

	// TODO:
	// parameters["logo"] = QString("logo");
	// parameters["description"] = QString("description");

	QJsonObject obj;
	obj["path"] = target();
	obj["access"] = QString("admin");
	obj["input"] = parameters;
	obj["output"] = QString("new id of team");
	return obj;
};

// --------------------------------------------------------------------

void TeamsInsert::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1006, "token are not found");
		return;
	} else if (!pUserSession->isAdmin()) {
		setErrorResponse(response, 1007, "this method only for admin");
		return;
	}

	QUrlQuery urlQuery(req->url());
	QString name = urlQuery.queryItemValue("name");
	QString ipserver = urlQuery.queryItemValue("ipserver");

	if (name.isEmpty()) {
		setErrorResponse(response, 1027, "Parameter name are not found or it is empty");
		return;
	}

	if (ipserver.isEmpty()) {
		setErrorResponse(response, 1028, "Parameter ipserver are not found or it is empty");
		return;
	} 

	QSqlQuery query(*db);
	query.prepare("INSERT INTO backend_teams(name, ipserver) VALUES(:name,:ipserver)");
	query.bindValue(":name", name);
	query.bindValue(":ipserver", ipserver);
	if (query.exec()) {
		response["result"] = QString("ok");
		response["id"] = query.lastInsertId().toInt();
	} else {
		setErrorResponse(response, 1029, query.lastError().text());
		return;
	}
};

// --------------------------------------------------------------------

// ********************************************************************
// ***************** TeamsUpdate **************************************
// ********************************************************************

QString TeamsUpdate::target() {
	return "/teams/update";
};

// --------------------------------------------------------------------

QJsonObject TeamsUpdate::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");
	parameters["id"] = QString("id of team");
	parameters["name"] = QString("name of team");
	parameters["ipserver"] = QString("ip server of team");

	// TODO:
	// parameters["logo"] = QString("logo");
	// parameters["description"] = QString("description");

	QJsonObject obj;
	obj["path"] = target();
	obj["access"] = QString("admin");
	obj["input"] = parameters;
	obj["output"] = QString("result");
	return obj;
};

// --------------------------------------------------------------------

void TeamsUpdate::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1011, "token are not found");
		return;
	} else if (!pUserSession->isAdmin()) {
		setErrorResponse(response, 1012, "this method only for admin");
		return;
	}
	
	QUrlQuery urlQuery(req->url());
	QString sId = urlQuery.queryItemValue("id");
	QString name = urlQuery.queryItemValue("name");
	QString ipserver = urlQuery.queryItemValue("ipserver");
	
	if (sId.isEmpty()) {
		setErrorResponse(response, 1033, "Parameter id are not found or it is empty");
		return;
	}
	
	if (name.isEmpty()) {
		setErrorResponse(response, 1035, "Parameter name are not found or it is empty");
		return;
	}

	if (ipserver.isEmpty()) {
		setErrorResponse(response, 1036, "Parameter ipserver are not found or it is empty");
		return;
	} 

	bool bConvert;
	int nId = sId.toInt(&bConvert, 10);
	if (!bConvert) {
		setErrorResponse(response, 1034, "Parameter id must be integer");
		return;
	}

	// TODO check exists record with this id
	QSqlQuery query(*db);
	query.prepare("UPDATE backend_teams SET name = :name, ipserver = :ipserver WHERE id = :id");
	query.bindValue(":name", name);
	query.bindValue(":ipserver", ipserver);
	query.bindValue(":id", nId);
	if (query.exec()) {
		response["result"] = QString("ok");
	} else {
		setErrorResponse(response, 1037, query.lastError().text());
		return;
	}
};

// --------------------------------------------------------------------

// ********************************************************************
// ***************** TeamsDelete **************************************
// ********************************************************************

QString TeamsDelete::target() {
	return "/teams/delete";
};

// --------------------------------------------------------------------

QJsonObject TeamsDelete::api() {
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

void TeamsDelete::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1008, "token are not found");
		return;
	} else if (!pUserSession->isAdmin()) {
		setErrorResponse(response, 1009, "this method only for admin");
		return;
	}
	
	QUrlQuery urlQuery(req->url());
	QString sId = urlQuery.queryItemValue("id");

	if (sId.isEmpty()) {
		setErrorResponse(response, 1030, "Parameter id are not found or it is empty");
		return;
	}
	
	bool bConvert;
	int nId = sId.toInt(&bConvert, 10);
	if (!bConvert) {
		setErrorResponse(response, 1031, "Parameter id must be integer");
		return;
	}

	QSqlQuery query(*db);
	query.prepare("DELETE FROM backend_teams WHERE id = :id");
	query.bindValue(":id", nId);
	if (query.exec()) {
		response["result"] = QString("ok");
	} else {
		setErrorResponse(response, 1032, query.lastError().text());
		return;
	}
};

// --------------------------------------------------------------------

} // namespace handlers
