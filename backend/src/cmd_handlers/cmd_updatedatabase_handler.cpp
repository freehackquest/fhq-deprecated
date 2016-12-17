#include "cmd_updatedatabase_handler.h"
#include <QJsonArray>
#include <QSqlError>
#include "../updates/create_list_updates.h"

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
	list << Errors::NotAuthorizedRequest().message();
	list << Errors::AllowedOnlyForAdmin().message();
	return list;
}

void CmdUpdateDatabaseHandler::handle(QWebSocket *pClient, IWebSocketServer *pWebSocketServer, QJsonObject obj){
	UserToken *pUserToken = pWebSocketServer->getUserToken(pClient);
	
	if(pUserToken == NULL){
		pWebSocketServer->sendMessageError(pClient, cmd(), Errors::NotAuthorizedRequest());
		return;
	}

	if(!pUserToken->isAdmin()){
		pWebSocketServer->sendMessageError(pClient, cmd(), Errors::AllowedOnlyForAdmin());
		return;
	}

	QSqlDatabase db = *(pWebSocketServer->database());

	QSqlQuery query(db);
	query.prepare("SELECT * FROM updates ORDER BY id ASC");
	query.exec();
	QString last_version;
	while (query.next()) {
		QSqlRecord record = query.record();
		int updateid = record.value("id").toInt();
		QString from_version = record.value("from_version").toString();
		QString version = record.value("version").toString();
		QString name = record.value("name").toString();
		QString description = record.value("description").toString();
		QString result = record.value("result").toString();
		int userid = record.value("userid").toInt();
		last_version = version;
	}

	qDebug() << "last_version:" << last_version;
	QVector<IUpdate *> vUpdates;
	create_list_updates(vUpdates);

	QJsonArray installedUpdates;

	bool bHasUpdates = true;
	while(bHasUpdates){
		bHasUpdates = false;
		for(int i = 0; i < vUpdates.size(); i++){
			IUpdate* pUpdate = vUpdates[i];
			if(last_version == pUpdate->from_version()){
				QJsonObject jsonUpdateData;
				jsonUpdateData["from_version"] = pUpdate->from_version();
				jsonUpdateData["version"] = pUpdate->version();
				jsonUpdateData["name"] = pUpdate->name();
				installedUpdates.push_back(jsonUpdateData);
				last_version = pUpdate->version();
				bHasUpdates = true;
				pUpdate->update(db);
				
				QSqlQuery insert_query(db);
				insert_query.prepare("INSERT INTO updates (from_version, version, name, description, result, userid, datetime_update) "
					  "VALUES (:from_version, :version, :name, :description, :result, :userid, NOW())");
				insert_query.bindValue(":from_version", pUpdate->from_version());
				insert_query.bindValue(":version", pUpdate->version());
				insert_query.bindValue(":name", pUpdate->name());
				insert_query.bindValue(":description", pUpdate->description());
				insert_query.bindValue(":result", "updated");
				insert_query.bindValue(":userid", pUserToken->userid());
				if(!insert_query.exec())
					qDebug() << "[ERROR] sql error: " << insert_query.lastError();
			}
		}
	}

	QJsonObject jsonData;
	jsonData["cmd"] = QJsonValue(cmd());
	jsonData["result"] = QJsonValue("DONE");
	jsonData["last_version"] = QJsonValue(last_version);
	jsonData["installed_updates"] = installedUpdates;
	pWebSocketServer->sendMessage(pClient, jsonData);
}
