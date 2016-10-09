#include "cmd_hello_handler.h"

QString CmdHelloHandler::cmd(){
	return "hello";
}

void CmdHelloHandler::handle(QWebSocket *pClient, IWebSocketServer *pWebSocketServer, QJsonObject obj){
	QJsonObject jsonData;
	jsonData["cmd"] = QJsonValue(cmd());
	pWebSocketServer->sendMessage(pClient, jsonData);
}
