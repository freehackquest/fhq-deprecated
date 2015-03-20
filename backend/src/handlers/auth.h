#ifndef AUTH_HANDLERS
#define AUTH_HANDLERS

#include <QJsonObject>
#include "../ihttphandler.h"

namespace handlers {

class AuthLogon : public IHTTPHandler {
	public:
		virtual QString target();
		virtual QJsonObject api();
		virtual void handleRequest(GlobalContext *, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response);
};

class AuthLogoff : public IHTTPHandler {
	public:
		virtual QString target();
		virtual QJsonObject api();
		virtual void handleRequest(GlobalContext *, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response);
};

} // namespace handlers

#endif // AUTH_HANDLERS
