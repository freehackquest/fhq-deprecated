#ifndef DAEMON_H
#define DAEMON_H

#include <QtCore>
#include <QString>
namespace adjd {
	struct config
	{
		public:
			QString strLogFile;
			QString db_name;
			QString db_user;
			QString db_pass;
	};

	void writeToLog(adjd::config &cnf, QString strMsg);
	int attackDefenceJuryDaemon(config &cnf);
	
}; // namespace adjd

#endif
