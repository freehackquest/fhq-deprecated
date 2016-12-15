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

	QStringList filters;
	QMap<QString,QString> filter_values;

	if(obj.contains("filter_text")){
		QString text = obj["filter_text"].toString().trimmed();
		if(text != ""){
			filters << "(email LIKE :email OR nick LIKE :nick)";
			filter_values[":email"] = "%" + text + "%";
			filter_values[":nick"] = "%" + text + "%";
		}
	}
	if(obj.contains("filter_role")){
		QString role = obj["filter_role"].toString().trimmed();
		if(role != ""){
			filters << "role = :role";
			filter_values[":role"] = role;
		}
	}


	QJsonArray users;
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
	pWebSocketServer->sendMessage(pClient, jsonData);
}
