#ifndef AUTH_LOGON
#define AUTH_LOGON

#include <QJsonObject>
#include "../ihttphandler.h"

namespace handlers {

class AuthLogon : public IHTTPHandler {
	public:
		virtual QString target();
		virtual QJsonObject api();
		virtual void handleRequest(QSqlDatabase &db, SecretToken *pToken, QHttpRequest *req, QJsonObject &response);
};

} // namespace handlers

#endif // AUTH_LOGON
