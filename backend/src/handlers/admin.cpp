#include <QJsonObject>
#include "../ihttphandler.h"
#include "admin.h"
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

};

// ********************************************************************
// ***************** AdminGameStart ***********************************
// ********************************************************************

QString AdminGameStart::target() {
	return "/admin/game/start";
};

// --------------------------------------------------------------------

QJsonObject AdminGameStart::api() {
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

void AdminGameStart::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
};

// --------------------------------------------------------------------

// ********************************************************************
// ***************** AdminGameStop **************************************
// ********************************************************************

QString AdminGameStop::target() {
	return "/admin/game/stop";
};

// --------------------------------------------------------------------

QJsonObject AdminGameStop::api() {
	QJsonObject parameters;
	parameters["token"] = QString("your token");
	parameters["id"] = QString("id of game");
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

void AdminGameStop::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {
};

// --------------------------------------------------------------------

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
	parameters["id"] = QString("id of team");

	QJsonObject obj;
	obj["path"] = target();
	obj["access"] = QString("admin");
	obj["input"] = parameters;
	obj["output"] = QString("result");
	return obj;
};

// --------------------------------------------------------------------

void AdminUserInsert::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {

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
	parameters["id"] = QString("id of team");

	QJsonObject obj;
	obj["path"] = target();
	obj["access"] = QString("admin");
	obj["input"] = parameters;
	obj["output"] = QString("result");
	return obj;
};

// --------------------------------------------------------------------

void AdminUserDelete::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {

};

// --------------------------------------------------------------------

} // namespace handlers
