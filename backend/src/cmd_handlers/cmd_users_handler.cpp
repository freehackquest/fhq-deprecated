#include "cmd_users_handler.h"

QString CmdUsersHandler::cmd(){
	return "users";
}

bool CmdUsersHandler::accessUnauthorized(){
	return false;
}

bool CmdUsersHandler::accessUser(){
	return false;
}

bool CmdUsersHandler::accessTester(){
	return false;
}

bool CmdUsersHandler::accessAdmin(){
	return true;
}

void CmdUsersHandler::handle(QWebSocket *pClient, IWebSocketServer *pWebSocketServer, QJsonObject obj){
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

	// TODO

	/*QJsonObject jsonData;
	jsonData["cmd"] = QJsonValue(cmd());
	jsonData["result"] = QJsonValue("DONE");
	pWebSocketServer->sendMessage(pClient, jsonData);*/
}
