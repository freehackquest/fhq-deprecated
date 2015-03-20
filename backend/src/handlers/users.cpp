#include <QJsonObject>
#include "../ihttphandler.h"
#include "users.h"
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
// ***************** UsersList ****************************************
// ********************************************************************

QString UsersList::target() {
	return "/users/list";
};

// --------------------------------------------------------------------

QJsonObject UsersList::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");

	QJsonObject obj;
	obj["path"] = target();
	obj["access"] = QString("authorized");
	obj["input"] = parameters;
	obj["output"] = QString("list of users");
	return obj;
};

// --------------------------------------------------------------------

void UsersList::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {

		
};

// ********************************************************************
// ***************** UsersInsert **************************************
// ********************************************************************

QString UsersInsert::target() {
	return "/users/insert";
};

// --------------------------------------------------------------------

QJsonObject UsersInsert::api() {
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

void UsersInsert::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {

};

// --------------------------------------------------------------------

// ********************************************************************
// ***************** UsersUpdate **************************************
// ********************************************************************

QString UsersUpdate::target() {
	return "/users/update";
};

// --------------------------------------------------------------------

QJsonObject UsersUpdate::api() {
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

void UsersUpdate::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {

};

// --------------------------------------------------------------------

// ********************************************************************
// ***************** UsersDelete **************************************
// ********************************************************************

QString UsersDelete::target() {
	return "/users/delete";
};

// --------------------------------------------------------------------

QJsonObject UsersDelete::api() {
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

void UsersDelete::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {

};

// --------------------------------------------------------------------

} // namespace handlers
