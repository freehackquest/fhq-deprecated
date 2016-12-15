#include "cmd_addnews_handler.h"

QString CmdAddNewsHandler::cmd(){
	return "addnews";
}

bool CmdAddNewsHandler::accessUnauthorized(){
	return false;
}

bool CmdAddNewsHandler::accessUser(){
	return false;
}

bool CmdAddNewsHandler::accessTester(){
	return false;
}

bool CmdAddNewsHandler::accessAdmin(){
	return true;
}

QString CmdAddNewsHandler::short_description(){
	return "some short description";
}

QString CmdAddNewsHandler::description(){
	return "some description";
}

QStringList CmdAddNewsHandler::errors(){
	QStringList	list;
	return list;
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
