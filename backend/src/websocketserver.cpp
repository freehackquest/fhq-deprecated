#include "websocketserver.h"

#include <QJsonDocument>
#include <QJsonObject>
#include <QDateTime>

#include <QHostAddress>
#include "cmd_handlers/create_cmd_handlers.h"


// QT_USE_NAMESPACE

// ---------------------------------------------------------------------

WebSocketServer::WebSocketServer(quint16 port, bool debug, QObject *parent) : QObject(parent) {
	m_pWebSocketServer = new QWebSocketServer(QStringLiteral("freehackquestd"), QWebSocketServer::NonSecureMode, this);
	m_debug = debug;
	m_sFilename = "/etc/freehackquestd/conf.ini";
	if(QFile::exists(m_sFilename)){
		QSettings sett(m_sFilename, QSettings::IniFormat);
		m_sDatabase_host = readStringFromSettings(sett, "DATABASE/host", "localhost");
		m_sDatabase_name = readStringFromSettings(sett, "DATABASE/name", "freehackquest");
		m_sDatabase_user = readStringFromSettings(sett, "DATABASE/user", "freehackquest_u");
		m_sDatabase_password = readStringFromSettings(sett, "DATABASE/password", "freehackquest_database_password");
		if (m_debug){
			qDebug() << "Database_host: " << m_sDatabase_host;
			qDebug() << "Database_name: " << m_sDatabase_name;
			qDebug() << "Database_user: " << m_sDatabase_user;
			qDebug() << "Database_password: " << m_sDatabase_password;
		}
		
		m_pDatabase = new QSqlDatabase(QSqlDatabase::addDatabase("QMYSQL"));
		m_pDatabase->setHostName(m_sDatabase_host);
		m_pDatabase->setDatabaseName(m_sDatabase_name);
		m_pDatabase->setUserName(m_sDatabase_user);
		m_pDatabase->setPassword(m_sDatabase_password);
		if (!m_pDatabase->open()){
			qDebug() << m_pDatabase->lastError().text();
			qDebug() << "Failed to connect.";
			return;
		}else{
			qDebug() << "Success connection to database";
		}
	}

    if (m_pWebSocketServer->listen(QHostAddress::Any, port)) {
        if (m_debug)
            qDebug() << "freehackquestd listening on port" << port;
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

QString WebSocketServer::readStringFromSettings(QSettings &sett, QString settName, QString defaultValue){
	QString sResult = defaultValue;
	if(sett.contains(settName)){
		sResult = sett.value(settName, sResult).toString();
	}else{
		qDebug() << "[WARNING] " << settName << " - not found in " << m_sFilename << "\n\t Will be used default value: " << defaultValue;
	}
	return sResult;
}

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
			QJsonObject jsonData;
			jsonData["cmd"] = QJsonValue(cmd);
			jsonData["error"] = QString("Unknown command");
			this->sendMessage(pClient, jsonData);
		}
	}else{
		QJsonObject jsonData;
		jsonData["error"] = QString("Invalid command format");
		this->sendMessage(pClient, jsonData);
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
		m_tokens.remove(pClient);
        m_clients.removeAll(pClient);
        pClient->deleteLater();
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

void WebSocketServer::sendToAll(QJsonObject obj){
	for(int i = 0; i < m_clients.size(); i++){
		this->sendMessage(m_clients.at(i), obj);
	}
}

// ---------------------------------------------------------------------

QSqlDatabase *WebSocketServer::database(){
	return m_pDatabase;
}

// ---------------------------------------------------------------------

void WebSocketServer::setUserToken(QWebSocket *pClient, UserToken *pUserToken){
	m_tokens[pClient] = pUserToken;
}

// ---------------------------------------------------------------------

UserToken * WebSocketServer::getUserToken(QWebSocket *pClient){
	if(m_tokens.contains(pClient)){
		return m_tokens[pClient];
	}
	return NULL;
}

