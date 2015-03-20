#ifndef SCOREBOARD_HANDLERS
#define SCOREBOARD_HANDLERS

#include <QJsonObject>
#include "../ihttphandler.h"

namespace handlers {

class Scoreboard : public IHTTPHandler {
	public:
		virtual QString target();
		virtual QJsonObject api();
		virtual void handleRequest(GlobalContext *, QSqlDatabase *db, QHttpRequest *req, QJsonObject &response);
};

} // namespace handlers

#endif // SCOREBOARD_HANDLERS
