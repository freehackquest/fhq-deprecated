#ifndef TEAMS_HANDLERS
#define TEAMS_HANDLERS

#include <QJsonObject>
#include "../ihttphandler.h"

namespace handlers {

class TeamsList : public IHTTPHandler {
	public:
		virtual QString target();
		virtual QJsonObject api();
		virtual void handleRequest(GlobalContext *, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response);
};

class TeamsInsert : public IHTTPHandler {
	public:
		virtual QString target();
		virtual QJsonObject api();
		virtual void handleRequest(GlobalContext *, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response);
};

class TeamsUpdate : public IHTTPHandler {
	public:
		virtual QString target();
		virtual QJsonObject api();
		virtual void handleRequest(GlobalContext *, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response);
};

class TeamsDelete : public IHTTPHandler {
	public:
		virtual QString target();
		virtual QJsonObject api();
		virtual void handleRequest(GlobalContext *, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response);
};

} // namespace handlers

#endif // TEAMS_HANDLERS
