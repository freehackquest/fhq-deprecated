#include "websocketserver.h"

#include <QJsonDocument>
#include <QJsonObject>
#include <QDateTime>
#include <QHostAddress>
#include "cmd_handlers/create_cmd_handlers.h"


// QT_USE_NAMESPACE

// ---------------------------------------------------------------------

WebSocketServer::WebSocketServer(quint16 port, bool debug, QObject *parent) : QObject(parent) {
	m_pWebSocketServer = new QWebSocketServer(QStringLiteral("FHQ Daemon"), QWebSocketServer::NonSecureMode, this);
	m_debug = debug;

    if (m_pWebSocketServer->listen(QHostAddress::Any, port)) {
        if (m_debug)
            qDebug() << "FHQ Daemon listening on port" << port;
        connect(m_pWebSocketServer, &QWebSocketServer::newConnection, this, &WebSocketServer::onNewConnection);
        connect(m_pWebSocketServer, &QWebSocketServer::closed, this, &WebSocketServer::closed);
        create_cmd_handlers(m_mapCmdHandlers);
    }
}

// ---------------------------------------------------------------------

WebSocketServer::~WebSocketServer() {
    m_pWebSocketServer->close();
    qDeleteAll(m_clients.begin(), m_clients.end());
}

// ---------------------------------------------------------------------

void WebSocketServer::onNewConnection()
{
    QWebSocket *pSocket = m_pWebSocketServer->nextPendingConnection();
	
	if (m_debug)
        qDebug() << "NewConnection " << pSocket->peerAddress().toString() << " " << pSocket->peerPort();
        
    connect(pSocket, &QWebSocket::textMessageReceived, this, &WebSocketServer::processTextMessage);
    connect(pSocket, &QWebSocket::binaryMessageReceived, this, &WebSocketServer::processBinaryMessage);
    connect(pSocket, &QWebSocket::disconnected, this, &WebSocketServer::socketDisconnected);

    m_clients << pSocket;
}

// ---------------------------------------------------------------------

void WebSocketServer::processTextMessage(QString message) {
    QWebSocket *pClient = qobject_cast<QWebSocket *>(sender());
    if (m_debug){
		qDebug() << QDateTime::currentDateTimeUtc().toString() << " [WS] <<< " << message;
	}

	QJsonDocument doc = QJsonDocument::fromJson(message.toUtf8());
	QJsonObject jsonData = doc.object();
	
	if(jsonData.contains("cmd")){
		QString cmd = jsonData["cmd"].toString();
		
		if(m_mapCmdHandlers.contains(cmd)){
			m_mapCmdHandlers[cmd]->handle(pClient, this, jsonData);
		}else{
			qDebug() << "Unknown command: " << cmd;
			this->sendMessage(pClient, QString("Unknown command"));
		}
	}else{
		this->sendMessage(pClient, QString("Invalid command format"));
	}
}

// ---------------------------------------------------------------------

void WebSocketServer::processBinaryMessage(QByteArray message) {
    QWebSocket *pClient = qobject_cast<QWebSocket *>(sender());
    if (m_debug)
        qDebug() << "Binary Message received:" << message;
    if (pClient) {
        pClient->sendBinaryMessage(message);
    }
}

// ---------------------------------------------------------------------

void WebSocketServer::socketDisconnected() {
    QWebSocket *pClient = qobject_cast<QWebSocket *>(sender());
    if (m_debug)
        qDebug() << "socketDisconnected:" << pClient;
    if (pClient) {
        m_clients.removeAll(pClient);
        pClient->deleteLater();
    }
}

// ---------------------------------------------------------------------

void WebSocketServer::sendMessage(QWebSocket *pClient, QString message){
	if (pClient) {
		pClient->sendTextMessage(message);
	}
}
	
// ---------------------------------------------------------------------

int WebSocketServer::getConnectedUsers(){
	return m_clients.length();
}

// ---------------------------------------------------------------------

void WebSocketServer::sendMessage(QWebSocket *pClient, QJsonObject obj){
	 if (pClient) {
		QJsonDocument doc(obj);
		QString message = doc.toJson(QJsonDocument::Compact);
		qDebug() << QDateTime::currentDateTimeUtc().toString() << " [WS] >>> " << message;
        pClient->sendTextMessage(message);
    }
}

// ---------------------------------------------------------------------

/*
FHQSettings *WebSocketServer::settings(){
	
}*/

