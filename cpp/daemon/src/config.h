#ifndef CONFIG_H
#define CONFIG_H

#include <QString>
#include <QMutex>

namespace adjd {
	struct db_conf
	{
		public:
			QString strLogFile;
			QString db_name;
			QString db_user;
			QString db_pass;
	};
	
	struct srvc_conf
	{
		public:
			int userID;
			int serviceID;
			int gameID;
			QString strUserIP;
			QString strServiceName;
			QString strServiceScript;
	};
	
	static QMutex g_mutexLog;
}; // namespace adjd

#endif // CONFIG
