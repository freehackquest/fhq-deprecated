#include <QJsonObject>
#include <QUuid>
#include <QtSql/QSqlDatabase>
#include <QtSql/QSqlError>
#include <QSqlQuery>
#include <QSqlRecord>
#include <QUrl>
#include <QUrlQuery>
#include <QJsonObject>
#include <QJsonArray>
#include <iostream>
#include <QUuid>
#include <QCryptographicHash>

#include "../qhttpserver/qhttprequest.h"
#include "../ihttphandler.h"
#include "services.h"
#include "../globalcontext.h"
#include "../usersession.h"

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
	parameters["token"] = QString("your token");

	QJsonObject obj;
	obj["path"] = target();
	obj["access"] = QString("admin");
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
	} else if (!pUserSession->isAdmin()) {
		setErrorResponse(response, 1037, "this method only for admin");
		return;
	}
	
	// TODO check exists record with this id
	QSqlQuery query(*db);
	query.prepare("SELECT * FROM backend_services");
	// query.bindValue(":name", name);
	if (query.exec()) {
		QSqlRecord rec = query.record();
		response["result"] = QString("ok");
		QJsonArray data;
		while (query.next()) {
			QJsonObject jsrec;
			jsrec["id"] = query.value(rec.indexOf("id")).toInt();
			jsrec["gameid"] = query.value(rec.indexOf("gameid")).toInt();
			jsrec["name"] = query.value(rec.indexOf("name")).toString();
			data.push_back(jsrec);
		}
		response["data"] = data;
	} else {
		setErrorResponse(response, 1053, query.lastError().text());
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
	parameters["gameid"] = QString("game of id");
	parameters["name"] = QString("name of service");

	QJsonObject obj;
	obj["access"] = QString("admin");
	obj["input"] = parameters;
	obj["output"] = QString("serviceid");
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
	
	QUrlQuery urlQuery(req->url());
	QString sGameId = urlQuery.queryItemValue("gameid");
	QString sName = urlQuery.queryItemValue("name");

	if (sGameId.isEmpty()) {
		setErrorResponse(response, 1040, "Parameter gameid are not found or it is empty");
		return;
	}

	if (sName.isEmpty()) {
		setErrorResponse(response, 1041, "Parameter name are not found or it is empty");
		return;
	} 

	bool bConvert;
	int nGameId = sGameId.toInt(&bConvert, 10);
	if (!bConvert) {
		setErrorResponse(response, 1045, "Parameter id must be integer");
		return;
	}

	// TODO check exists game and game is attack-defence

	QSqlQuery query(*db);
	query.prepare("INSERT INTO backend_services(gameid, name) VALUES(:gameid,:name)");
	query.bindValue(":gameid", nGameId);
	query.bindValue(":name", sName);
	if (query.exec()) {
		response["result"] = QString("ok");
		response["id"] = query.lastInsertId().toInt();
	} else {
		setErrorResponse(response, 1042, query.lastError().text());
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
	parameters["id"] = QString("id of service");
	parameters["name"] = QString("name of service");

	QJsonObject obj;
	obj["access"] = QString("admin");
	obj["input"] = parameters;
	obj["output"] = QString("result");
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
	
	QUrlQuery urlQuery(req->url());
	QString sId = urlQuery.queryItemValue("id");
	QString name = urlQuery.queryItemValue("name");
	
	if (sId.isEmpty()) {
		setErrorResponse(response, 1049, "Parameter id are not found or it is empty");
		return;
	}
	
	if (name.isEmpty()) {
		setErrorResponse(response, 1050, "Parameter name are not found or it is empty");
		return;
	}

	bool bConvert;
	int nId = sId.toInt(&bConvert, 10);
	if (!bConvert) {
		setErrorResponse(response, 1051, "Parameter id must be integer");
		return;
	}

	// TODO check exists record with this id

	QSqlQuery query(*db);
	query.prepare("UPDATE backend_services SET name = :name WHERE id = :id");
	query.bindValue(":name", name);
	query.bindValue(":id", nId);
	if (query.exec()) {
		response["result"] = QString("ok");
	} else {
		setErrorResponse(response, 1052, query.lastError().text());
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
	obj["access"] = QString("admin");
	obj["input"] = parameters;
	obj["path"] = target();
	obj["output"] = QString("result");
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
	
	QUrlQuery urlQuery(req->url());
	QString sId = urlQuery.queryItemValue("id");

	if (sId.isEmpty()) {
		setErrorResponse(response, 1046, "Parameter id are not found or it is empty");
		return;
	}
	
	bool bConvert;
	int nId = sId.toInt(&bConvert, 10);
	if (!bConvert) {
		setErrorResponse(response, 1047, "Parameter id must be integer");
		return;
	}

	// TODO check exists this service

	QSqlQuery query(*db);
	query.prepare("DELETE FROM backend_services WHERE id = :id");
	query.bindValue(":id", nId);
	if (query.exec()) {
		response["result"] = QString("ok");
	} else {
		setErrorResponse(response, 1048, query.lastError().text());
		return;
	}
};

// --------------------------------------------------------------------

// ********************************************************************
// ***************** ServicesUploadChecker **************************************
// ********************************************************************

QString ServicesUploadChecker::target() {
	return "/services/uploadchecker";
};

// --------------------------------------------------------------------

QJsonObject ServicesUploadChecker::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");

	QJsonObject obj;
	obj["access"] = QString("authorized");
	obj["input"] = parameters;
	obj["path"] = target();
	return obj;
};

// --------------------------------------------------------------------

void ServicesUploadChecker::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
	UserSession *pUserSession = pGlobalContext->userSession(req->url(), db);
	if (pUserSession == NULL) {
		setErrorResponse(response, 1043, "token are not found");
		return;
	} else if (!pUserSession->isAdmin()) {
		setErrorResponse(response, 1044, "this method only for admin");
		return;
	}
	setErrorResponse(response, 9999, "this method are not implemented");
};

// --------------------------------------------------------------------

} // namespace handlers
