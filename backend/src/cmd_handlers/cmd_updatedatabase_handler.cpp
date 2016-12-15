#include "cmd_updatedatabase_handler.h"
#include <QJsonArray>

QString CmdUpdateDatabaseHandler::cmd(){
	return "updatedatabase";
}

bool CmdUpdateDatabaseHandler::accessUnauthorized(){
	return false;
}

bool CmdUpdateDatabaseHandler::accessUser(){
	return false;
}

bool CmdUpdateDatabaseHandler::accessTester(){
	return false;
}

bool CmdUpdateDatabaseHandler::accessAdmin(){
	return true;
}

QString CmdUpdateDatabaseHandler::short_description(){
	return "Updating database";
}

QString CmdUpdateDatabaseHandler::description(){
	return "The algorithm will check the version of the database structure and update if necessary";
}

QStringList CmdUpdateDatabaseHandler::errors(){
	QStringList	list;
	return list;
}

void CmdUpdateDatabaseHandler::handle(QWebSocket *pClient, IWebSocketServer *pWebSocketServer, QJsonObject obj){
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
		jsonData["error"] = QJsonValue("Allowed only for admin");
		pWebSocketServer->sendMessage(pClient, jsonData);
		return;
	}

	/*QJsonArray users;
	QSqlDatabase db = *(pWebSocketServer->database());
	
	QSqlQuery query(db);
	QString where = filters.join(" AND "); 
	if(where.length() > 0){
		where = "WHERE " + where;
	}
	query.prepare("SELECT * FROM users " + where + " ORDER BY dt_last_login DESC");
	foreach(QString key, filter_values.keys() ){
		query.bindValue(key, filter_values.value(key));
	}
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
	pWebSocketServer->sendMessage(pClient, jsonData);*/
}
