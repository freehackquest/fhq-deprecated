#include "cmd_login_handler.h"

QString CmdLoginHandler::cmd(){
	return "login";
}

void CmdLoginHandler::handle(QWebSocket *pClient, IWebSocketServer *pWebSocketServer, QJsonObject obj){
	QJsonObject jsonData;
	jsonData["cmd"] = QJsonValue(cmd());
	
	if(!obj.contains("token")){
		jsonData["result"] = QJsonValue("FAIL");
		jsonData["error"] = QJsonValue("Not found requred parameter token");
		pWebSocketServer->sendMessage(pClient, jsonData);
		return;
	}
	
	QSqlDatabase db = *(pWebSocketServer->database());
	QSqlQuery query(db);
	query.prepare("SELECT * FROM users_tokens WHERE token = :token");
	query.bindValue(":token", obj["token"].toString());
	query.exec();
	if (query.next()) {
		QSqlRecord record = query.record();
		int userid = record.value("userid").toInt();
		QString status = record.value("status").toString();
		QString data = record.value("data").toString();
		QString start_date = record.value("start_date").toString();
		QString end_date = record.value("end_date").toString();
		qDebug() << "userid " << userid;
		qDebug() << "status " << status;
		qDebug() << "data " << data;
		qDebug() << "start_date " << start_date;
		qDebug() << "end_date " << end_date;
		pWebSocketServer->setUserToken(pClient, new UserToken(data));
	}else{
		jsonData["result"] = QJsonValue("FAIL");
		jsonData["error"] = QJsonValue("Invalid token");
		pWebSocketServer->sendMessage(pClient, jsonData);
		return;
	}

	jsonData["result"] = QJsonValue("DONE");
	pWebSocketServer->sendMessage(pClient, jsonData);
}