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
HandlerManager::HandlerManager(GlobalContext *pGlobalContext)
{
	m_pGlobalContext = pGlobalContext;
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

void HandlerManager::startServer() {
  m_pServer = new QHttpServer(this);
  int nServerPort = m_pGlobalContext->getServerPort();
  connect(m_pServer, SIGNAL(newRequest(QHttpRequest*, QHttpResponse*)),
		this, SLOT(handleRequest(QHttpRequest*, QHttpResponse*)));
	m_pServer->listen(QHostAddress::Any, nServerPort);
  std::cout << "Started server on " << nServerPort << " port.\n";
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
		AutoDatabaseConnection autodb(m_pGlobalContext);
		if (!autodb.isEmpty()) {
			m_mapHandlers[target]->handleRequest(m_pGlobalContext, autodb.db(), req, response);
		} else {
			setErrorResponse(response, 2001, "Problem with database");
		}
	} else {
		setErrorResponse(response, 2000, "Handler did not found");
	}

	QByteArray body = QJsonDocument(response).toJson();
	resp->setHeader("Content-Length", QString::number(body.size()));
	resp->writeHead(200);
	resp->end(body);
}
