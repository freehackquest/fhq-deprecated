#include "cmd_getconnectedusers_handler.h"

QString CmdGetConnectedUsersHandler::cmd(){
	return "getconnectedusers";
}

void CmdGetConnectedUsersHandler::handle(QWebSocket *pClient, IWebSocketServer *pWebSocketServer, QJsonObject obj){
	QJsonObject jsonData;
	jsonData["cmd"] = QJsonValue(cmd());
	jsonData["connectedusers"] = pWebSocketServer->getConnectedUsers();
	pWebSocketServer->sendMessage(pClient, jsonData);
}
