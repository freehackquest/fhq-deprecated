#include "cmd_getpublicinfo_handler.h"

QString CmdGetPublicInfoHandler::cmd(){
	return "getpublicinfo";
}

bool CmdGetPublicInfoHandler::accessUnauthorized(){
	return true;
}

bool CmdGetPublicInfoHandler::accessUser(){
	return true;
}

bool CmdGetPublicInfoHandler::accessTester(){
	return true;
}

bool CmdGetPublicInfoHandler::accessAdmin(){
	return true;
}

void CmdGetPublicInfoHandler::handle(QWebSocket *pClient, IWebSocketServer *pWebSocketServer, QJsonObject obj){
	QJsonObject jsonData;
	jsonData["cmd"] = QJsonValue(cmd());
	jsonData["connectedusers"] = pWebSocketServer->getConnectedUsers();
	pWebSocketServer->sendMessage(pClient, jsonData);
}
