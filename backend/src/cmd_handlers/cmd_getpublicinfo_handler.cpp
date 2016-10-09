#include "cmd_getpublicinfo_handler.h"

QString CmdGetPublicInfoHandler::cmd(){
	return "getpublicinfo";
}

void CmdGetPublicInfoHandler::handle(QWebSocket *pClient, IWebSocketServer *pWebSocketServer, QJsonObject obj){
	QJsonObject jsonData;
	jsonData["cmd"] = QJsonValue(cmd());
	jsonData["connectedusers"] = pWebSocketServer->getConnectedUsers();
	pWebSocketServer->sendMessage(pClient, jsonData);
}
