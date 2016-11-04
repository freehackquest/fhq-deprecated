#include "cmd_send_letters_to_subscribers_handler.h"

QString CmdSendLettersToSubscribersHandler::cmd(){
	return "send_letters_to_subscribers";
}

void CmdSendLettersToSubscribersHandler::handle(QWebSocket *pClient, IWebSocketServer *pWebSocketServer, QJsonObject obj){
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

	qDebug() << "send_letters_to_subscribers!!!";
	
	QStringList emails;
	emails << "sea-kg@ya.ru";
	emails << "mrseakg@gmail.com";
	QString text = "Test email again";
	pWebSocketServer->sendLettersBcc(emails, "FreeHackQuest News", text);

	QJsonObject jsonData;
	jsonData["cmd"] = QJsonValue(cmd());
	jsonData["result"] = QJsonValue("DONE");
	pWebSocketServer->sendMessage(pClient, jsonData);
}
