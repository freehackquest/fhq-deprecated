#include "cmd_users_handler.h"
#include <QJsonArray>

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

	QJsonArray users;

	QSqlDatabase db = *(pWebSocketServer->database());
	QSqlQuery query(db);
	query.prepare("SELECT * FROM users ORDER BY dt_last_login DESC");
	query.exec();
	while (query.next()) {
		QSqlRecord record = query.record();
		int userid = record.value("id").toInt();
		QString uuid = record.value("uuid").toString();
		QString email = record.value("email").toString();
		QString nick = record.value("nick").toString();
		QJsonObject user;
		user["id"] = userid;
		user["uuid"] = uuid;
		user["nick"] = nick;
		user["email"] = email;
		users.push_back(user);
	}

	QJsonObject jsonData;
	jsonData["cmd"] = QJsonValue(cmd());
	jsonData["result"] = QJsonValue("DONE");
	jsonData["data"] = users;
	pWebSocketServer->sendMessage(pClient, jsonData);
}
