#ifndef GAMES_HANDLERS
#define GAMES_HANDLERS

#include <QJsonObject>
#include "../ihttphandler.h"

namespace handlers {

class GamesList : public IHTTPHandler {
	public:
		virtual QString target();
		virtual QJsonObject api();
		virtual void handleRequest(GlobalContext *, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response);
};

class GamesInsert : public IHTTPHandler {
	public:
		virtual QString target();
		virtual QJsonObject api();
		virtual void handleRequest(GlobalContext *, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response);
};

class GamesUpdate : public IHTTPHandler {
	public:
		virtual QString target();
		virtual QJsonObject api();
		virtual void handleRequest(GlobalContext *, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response);
};

class GamesDelete : public IHTTPHandler {
	public:
		virtual QString target();
		virtual QJsonObject api();
		virtual void handleRequest(GlobalContext *, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response);
};

} // namespace handlers

#endif // GAMES_HANDLERS
