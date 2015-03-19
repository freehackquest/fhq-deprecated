#include "qhttpserver/qhttpserver.h"
#include "qhttpserver/qhttprequest.h"
#include "qhttpserver/qhttpresponse.h"
#include "SecretToken.h"
#include <QJsonDocument>
#include <QJsonObject>
#include <iostream>
#include <QMap>
#include <QtSql/QSqlError>
#include <QSqlQuery>
#include <QSqlRecord>

SecretToken::SecretToken(QString token) {
	m_sToken = token;
};

// --------------------------------------------------------------------

bool SecretToken::loadToken(QSqlDatabase &db) {
	QSqlQuery query(db);
	query.prepare("SELECT data FROM backend_users_tokens WHERE token = :token");
	query.bindValue(":token", m_sToken);
	query.exec();
	QSqlRecord rec = query.record();
	if (query.next()) {
		QString data = query.value(rec.indexOf("data")).toString();
		std::cout << data.toStdString() << "\n";
		QJsonDocument doc = QJsonDocument::fromJson(data.toLatin1());
		m_pObj = doc.object();
		return true;
	}
	return false;
};

// --------------------------------------------------------------------

bool SecretToken::saveToken(QSqlDatabase &db) {
	QByteArray body = QJsonDocument(m_pObj).toJson();
	QSqlQuery query(db);
	query.prepare("INSERT INTO backend_users_tokens (token,status,data,dt_start,dt_end) VALUES (?,?,?, NOW(), NOW()) "
	" ON DUPLICATE KEY UPDATE status=?, data=?, dt_end=NOW()");
	query.addBindValue(m_sToken);
	query.addBindValue(QString("active"));
	query.addBindValue(QString(body));
	query.addBindValue(QString("active"));
	query.addBindValue(QString(body));
	return query.exec();
};

// --------------------------------------------------------------------

QJsonObject &SecretToken::getJson() {
	return m_pObj;
}

// --------------------------------------------------------------------

int SecretToken::getUserID() {
	if (m_pObj.contains("userid"))
		return m_pObj.value("userid").toInt();
	return 0;
}

// --------------------------------------------------------------------
