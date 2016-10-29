#include "cmd_addnews_handler.h"

QString CmdAddNewsHandler::cmd(){
	return "addnews";
}

void CmdAddNewsHandler::handle(QWebSocket *pClient, IWebSocketServer *pWebSocketServer, QJsonObject obj){
	UserToken *pUserToken = pWebSocketServer->getUserToken(pClient);
	if(pUserToken == NULL){
		QJsonObject jsonData;
		jsonData["cmd"] = QJsonValue(cmd());
		jsonData["result"] = QJsonValue("FAIL");
		jsonData["error"] = QJsonValue("Not authorized request");
		pWebSocketServer->sendMessage(pClient, jsonData);
		return;
	}

	if(!pUserToken->isAdmin()){
		QJsonObject jsonData;
		jsonData["cmd"] = QJsonValue(cmd());
		jsonData["result"] = QJsonValue("FAIL");
		jsonData["error"] = QJsonValue("Allowed only fot admin");
		pWebSocketServer->sendMessage(pClient, jsonData);
		return;
	}

	QJsonObject jsonData;
	jsonData["cmd"] = QJsonValue(cmd());
	jsonData["result"] = QJsonValue("DONE");
	pWebSocketServer->sendMessage(pClient, jsonData);
	
	QJsonObject jsonData2;
	jsonData2["cmd"] = QJsonValue("news");
	jsonData2["type"] = obj["type"];
	jsonData2["message"] = obj["message"];

	pWebSocketServer->sendToAll(jsonData2);
}
