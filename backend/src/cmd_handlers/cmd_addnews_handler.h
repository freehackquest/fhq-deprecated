#ifndef CMD_ADDNEWS_HANDLER_H
#define CMD_ADDNEWS_HANDLER_H

#include "../interfaces/icmdhandler.h"
#include "../interfaces/iwebsocketserver.h"

#include <QString>
#include <QVariant>

class CmdAddNewsHandler : public ICmdHandler {
	
	public:
		virtual QString cmd();
		virtual void handle(QWebSocket *pClient, IWebSocketServer *pWebSocketServer, QJsonObject obj);
};

#endif // CMD_ADDNEWS_HANDLER_H
