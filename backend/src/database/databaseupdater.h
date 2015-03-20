#ifndef DATABASEUPDATER
#define DATABASEUPDATER

#include <QSettings>
#include "../globalcontext.h"

class IUpdate {
	public:
		virtual QString fromVersion() = 0;
		virtual QString toVersion() = 0;
		virtual QString text() = 0;
		virtual bool update(QSqlDatabase *db) = 0;
};


class DatabaseUpdater {
	public:
		void update(GlobalContext *pGlobalContext);
		void create(GlobalContext *pGlobalContext);
	private:
		void insertUpdate(QSqlDatabase *db, QString name, QString version);
		QString getLastUpdate(QSqlDatabase *db);
		bool update0000(QSqlDatabase *db);
};

#endif // DATABASEUPDATER
