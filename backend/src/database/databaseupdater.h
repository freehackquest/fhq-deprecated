#ifndef DATABASEUPDATER
#define DATABASEUPDATER

#include <QSettings>


class DatabaseUpdater {
	public:
		void update(QSettings *set);
		
	private:
		void insertUpdate(QSqlDatabase &db, QString name, QString version);
		QString getLastUpdate(QSqlDatabase &db);
		
		void update0000(QSqlDatabase &db);
		void update0001(QSqlDatabase &db);
		void update0002(QSqlDatabase &db);
		void update0003(QSqlDatabase &db);
};

#endif // DATABASEUPDATER
