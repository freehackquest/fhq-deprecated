#include "qhttpserver/qhttpserver.h"
#include "qhttpserver/qhttprequest.h"
#include "qhttpserver/qhttpresponse.h"
#include "usersession.h"
#include <QJsonDocument>
#include <QJsonObject>
#include <iostream>
#include <QMap>
#include <QtSql/QSqlError>
#include <QSqlQuery>
#include <QSqlRecord>

UserSession::UserSession() {
};

// --------------------------------------------------------------------

void UserSession::setToken(QString sToken) {
	m_sToken = sToken;
}

// --------------------------------------------------------------------

QString UserSession::token() {
	return m_sToken;
}

// --------------------------------------------------------------------
		
QJsonObject &UserSession::json() {
	return m_pDataToken;
}

// --------------------------------------------------------------------
		
int UserSession::userID() {
	if (m_pDataToken.contains("userid"))
		return m_pDataToken.value("userid").toInt();
	return 0;
}

// --------------------------------------------------------------------
