#ifndef CMD_SEND_LETTERS_TO_SUBSCRIBERS_HANDLER_H
#define CMD_SEND_LETTERS_TO_SUBSCRIBERS_HANDLER_H

#include "../interfaces/icmdhandler.h"
#include "../interfaces/iwebsocketserver.h"

#include <QString>
#include <QVariant>

class CmdSendLettersToSubscribersHandler : public ICmdHandler {
	
	public:
		virtual QString cmd();
		virtual bool accessUnauthorized();
		virtual bool accessUser();
		virtual bool accessTester();
		virtual bool accessAdmin();
		virtual void handle(QWebSocket *pClient, IWebSocketServer *pWebSocketServer, QJsonObject obj);
};

#endif // CMD_SEND_LETTERS_TO_SUBSCRIBERS_HANDLER_H