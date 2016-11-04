#ifndef INTERFACES_IWEBSOCKETSERVER_H
#define INTERFACES_IWEBSOCKETSERVER_H

#include <QJsonDocument>
#include <QJsonObject>
#include <QWebSocket>
#include <QSqlDatabase>
#include <QSqlQuery>
#include <QSqlRecord>

#include "icmdhandler.h"
#include "../usertoken.h"

class IWebSocketServer {
	public:
		virtual void sendMessage(QWebSocket *pClient, QJsonObject obj) = 0;
		virtual void sendToAll(QJsonObject obj) = 0;
		virtual int getConnectedUsers() = 0;
		virtual QSqlDatabase *database() = 0;
		virtual void setUserToken(QWebSocket *pClient, UserToken *pUserToken) = 0;
		virtual UserToken * getUserToken(QWebSocket *pClient) = 0;
};

#endif // INTERFACES_IWEBSOCKETSERVER_H