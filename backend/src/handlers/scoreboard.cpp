#include <QJsonObject>
#include "../ihttphandler.h"
#include "scoreboard.h"
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
// ***************** Scoreboard ****************************************
// ********************************************************************

QString Scoreboard::target() {
	return "/scoreboard";
};

// --------------------------------------------------------------------

QJsonObject Scoreboard::api() {
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

void Scoreboard::handleRequest(GlobalContext *pGlobalContext, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response) {	
};


} // namespace handlers
