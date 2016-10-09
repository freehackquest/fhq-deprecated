#include "cmd_addnews_handler.h"

QString CmdAddNewsHandler::cmd(){
	return "addnews";
}

void CmdAddNewsHandler::handle(QWebSocket *pClient, IWebSocketServer *pWebSocketServer, QJsonObject obj){
	QJsonObject jsonData;
	jsonData["cmd"] = QJsonValue(cmd());
	jsonData["result"] = QJsonValue("DONE");
	pWebSocketServer->sendMessage(pClient, jsonData);
	
	QJsonObject jsonData2;
	jsonData2["cmd"] = QJsonValue("news");
	jsonData2["type"] = obj["type"];
	jsonData2["message"] = obj["message"];

	// TODO check admin access
	// pWebSocketServer->sendToAll(jsonData2);
}
