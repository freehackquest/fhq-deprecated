#include "cmd_hello_handler.h"

QString CmdHelloHandler::cmd(){
	return "hello";
}

bool CmdHelloHandler::accessUnauthorized(){
	return true;
}

bool CmdHelloHandler::accessUser(){
	return true;
}

bool CmdHelloHandler::accessTester(){
	return true;
}

bool CmdHelloHandler::accessAdmin(){
	return true;
}

void CmdHelloHandler::handle(QWebSocket *pClient, IWebSocketServer *pWebSocketServer, QJsonObject obj){
	QJsonObject jsonData;
	jsonData["cmd"] = QJsonValue(cmd());
	pWebSocketServer->sendMessage(pClient, jsonData);
}
