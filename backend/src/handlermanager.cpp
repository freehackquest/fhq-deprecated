#include "qhttpserver/qhttpserver.h"
#include "qhttpserver/qhttprequest.h"
#include "qhttpserver/qhttpresponse.h"
#include "handlermanager.h"
#include <QJsonDocument>
#include <QJsonObject>
#include <iostream>
#include <QMap>
#include <QtSql/QSqlError>
#include <QSqlQuery>
#include <QSqlRecord>
#include <QUrl>
#include <QUrlQuery>

void setErrorResponse(QJsonObject &response, int code, QString message) {
	response["result"] = QString("fail");
	QJsonObject error;
	error["code"] = code;
	error["message"] = message;
	response["error"] = error;
};

/// HandlerManager
HandlerManager::HandlerManager()
{
	
}

// --------------------------------------------------------------------

void HandlerManager::setServer(QHttpServer *server) {
	connect(server, SIGNAL(newRequest(QHttpRequest*, QHttpResponse*)),
		this, SLOT(handleRequest(QHttpRequest*, QHttpResponse*)));
}

// --------------------------------------------------------------------

void HandlerManager::setConfigFile(QString configFile) {
	m_pSettings = new QSettings(configFile, QSettings::IniFormat);
}

// --------------------------------------------------------------------

QSettings *HandlerManager::getSettings() {
	return m_pSettings;
}

// --------------------------------------------------------------------

int HandlerManager::getServerPort() {
	return m_pSettings->value("server/port", 8010).toInt();
}

// --------------------------------------------------------------------

void HandlerManager::RegisterHTTPHandler(IHTTPHandler* pHandler) {
	m_mapHandlers[pHandler->target()] = pHandler;
}

// --------------------------------------------------------------------

void HandlerManager::UnregisterHTTPHandler(IHTTPHandler* pHandler) {
	m_mapHandlers.remove(pHandler->target());
}

// --------------------------------------------------------------------

void HandlerManager::handleRequest(QHttpRequest *req, QHttpResponse *resp)
{
	Q_UNUSED(req);
	QString target = req->path();
	QJsonObject response;

	if (target == "/") {
		QMap<QString, IHTTPHandler*>::iterator i;
		for (i = m_mapHandlers.begin(); i != m_mapHandlers.end(); ++i) {
			response[i.key()] = i.value()->api();
		}
	} else if (m_mapHandlers.contains(target)) {
		QSqlDatabase db = QSqlDatabase::addDatabase("QMYSQL", "handleRequest"); // todo check name connection
		db.setHostName(m_pSettings->value("database/host", "localhost").toString());
		db.setDatabaseName(m_pSettings->value("database/dbname", "fhq").toString());
		db.setUserName(m_pSettings->value("database/dbuser", "fhq").toString());
		db.setPassword(m_pSettings->value("database/dbuserpassword", "fhq").toString());
		if (!db.open()){
			setErrorResponse(response, 403, db.lastError().text());
			std::cerr << "[ERROR] " << db.lastError().text().toStdString() << "\n";
			std::cerr << "[ERROR] Failed to connect.\n";
			return;
		}

		QUrlQuery urlQuery(req->url());
		QString sToken = urlQuery.queryItemValue("token");
		SecretToken *pToken = NULL; 
		if (!sToken.isEmpty()) {
			pToken = new SecretToken(sToken);
			if (!pToken->loadToken(db)) {
				delete pToken;
				pToken = NULL;
			}
		}
		
		m_mapHandlers[target]->handleRequest(db, pToken, req, response);

	} else {
		setErrorResponse(response, 404, "Handler did not found");
	}

	QByteArray body = QJsonDocument(response).toJson();
	resp->setHeader("Content-Length", QString::number(body.size()));
	resp->writeHead(200);
	resp->end(body);
}
