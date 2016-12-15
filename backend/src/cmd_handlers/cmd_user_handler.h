#ifndef CMD_USER_HANDLER_H
#define CMD_USER_HANDLER_H

#include "../interfaces/icmdhandler.h"
#include "../interfaces/iwebsocketserver.h"

#include <QString>
#include <QVariant>

class CmdUserHandler : public ICmdHandler {
	
	public:
		CmdUserHandler();
		virtual QString cmd();
		virtual bool accessUnauthorized();
		virtual bool accessUser();
		virtual bool accessTester();
		virtual bool accessAdmin();
		virtual QString short_description();
		virtual QString description();
		virtual QStringList errors();
		virtual void handle(QWebSocket *pClient, IWebSocketServer *pWebSocketServer, QJsonObject obj);
		
	private:
		QString m_sERR_NO_FOUND_UUID_FIELD;
};

#endif // CMD_USER_HANDLER_H
