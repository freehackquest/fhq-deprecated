#include <QJsonObject>
#include "../ihttphandler.h"
#include "src/handlers/auth_logon.h"
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

QString AuthLogon::target() {
	return "/auth/logon";
};

// --------------------------------------------------------------------

QJsonObject AuthLogon::api() {
	QJsonObject parameters;
	parameters["name"] = QString("your name");
	parameters["password"] = QString("your password");

	QJsonObject obj;
	obj["access"] = QString("unauthorized");
	obj["input"] = parameters;
	obj["path"] = target();
	return obj;
};

// --------------------------------------------------------------------

void AuthLogon::handleRequest(QSqlDatabase &db, SecretToken *pToken, QHttpRequest *req, QJsonObject &response) {
	QString uuid = QUuid::createUuid().toString();
	uuid = uuid.mid(1,uuid.length()-2);
	QUrlQuery urlQuery(req->url());
	
	QString name = urlQuery.queryItemValue("name");
	QString password = urlQuery.queryItemValue("password");
	
	if (name.isEmpty()) {
		setErrorResponse(response, 1003, "Parameter name are not found.");
	} else if (password.isEmpty()) {
		setErrorResponse(response, 1004, "Parameter password are not found.");
	} else {
		QSqlQuery query(db);
		query.prepare("SELECT * FROM backend_users WHERE name = :name and password = :password");
		query.bindValue(":name", name);
		query.bindValue(":password", QString(QCryptographicHash::hash((password.toUtf8()),QCryptographicHash::Md5).toHex()));
		query.exec();
		QSqlRecord rec = query.record();

		if (query.next()) {
			response["result"] = QString("ok");
			response["token"] = uuid;
			pToken = new SecretToken(uuid);
			pToken->getJson()["userid"] = query.value(rec.indexOf("id")).toInt();
			pToken->getJson()["name"] = query.value(rec.indexOf("name")).toString();
			pToken->getJson()["role"] = query.value(rec.indexOf("role")).toString();
			pToken->saveToken(db);
		} else {
			setErrorResponse(response, 1002, "Name or password are incorrect.");
		}
		// response["password"] = QString(QCryptographicHash::hash((password.toUtf8()),QCryptographicHash::Md5).toHex());
	}
};

// --------------------------------------------------------------------

} // namespace handlers
