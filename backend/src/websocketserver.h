#ifndef WEBSOCKETSERVER_H
#define WEBSOCKETSERVER_H

#include <QObject>
#include <QString>
#include <QWebSocket>
#include <QWebSocketServer>
#include <QMap>
#include "interfaces/icmdhandler.h"
#include "interfaces/iwebsocketserver.h"

// QT_FORWARD_DECLARE_CLASS(QWebSocketServer)
// QT_FORWARD_DECLARE_CLASS(QWebSocket)

class WebSocketServer : public QObject, public IWebSocketServer {
	
	private:
		Q_OBJECT
	public:
		explicit WebSocketServer(quint16 port, bool debug = false, QObject *parent = Q_NULLPTR);
		~WebSocketServer();

		void sendMessage(QWebSocket *pClient, QString message);
		virtual int getConnectedUsers();
		virtual void sendMessage(QWebSocket *pClient, QJsonObject obj);
		// virtual FHQSettings *settings();
		
	Q_SIGNALS:
		void closed();

	private Q_SLOTS:
		void onNewConnection();
		void processTextMessage(QString message);
		void processBinaryMessage(QByteArray message);
		void socketDisconnected();

	private:
		QWebSocketServer *m_pWebSocketServer;
		QList<QWebSocket *> m_clients;
		QMap<QString, ICmdHandler *> m_mapCmdHandlers;
		bool m_debug;
};

#endif //WEBSOCKETSERVER_H
