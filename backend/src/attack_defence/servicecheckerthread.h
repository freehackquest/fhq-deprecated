#ifndef SERVICECHECKERTHREAD_H
#define SERVICECHECKERTHREAD_H
 
#include <QThread>
#include <QMutex>
#include <QWaitCondition>
// #include "config.h"

class ServiceCheckerThread : public QThread
{
	public:
		ServiceCheckerThread(const adjd::db_conf &db_cnf, const adjd::srvc_conf &srvs_cnf);
	private:
		adjd::srvc_conf srvs_cnf;
		adjd::db_conf db_cnf;
		void updateFlags(QString flag, bool bWorked);
	public:
		void run();
};


#endif // SERVICECHECKERTHREAD_H
