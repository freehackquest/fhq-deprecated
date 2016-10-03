#ifndef INTERFACES_IWEBSOCKETSERVER_H
#define INTERFACES_IWEBSOCKETSERVER_H

#include <QJsonObject>
#include <QWebSocket>
#include "icmdhandler.h"
// #include "../fhqsettings.h"

class IWebSocketServer {
	public:
		virtual void sendMessage(QWebSocket *pClient, QJsonObject obj) = 0;
		virtual int getConnectedUsers() = 0;
		// virtual FHQSettings *settings() = 0;
};

#endif // INTERFACES_IWEBSOCKETSERVER_H
