#ifndef SPECIALTHREAD_H
#define SPECIALTHREAD_H
 
#include <QThread>
#include <QMutex>
#include <QWaitCondition>
#include "config.h"
// struct 

namespace adjd {

void writeToLog(adjd::db_conf &cnf, QString strMsg);

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

class SleepSimulator {
     QMutex localMutex;
     QWaitCondition sleepSimulator;

public:
    SleepSimulator();
    void sleep(unsigned long sleepMS);
    void CancelSleep();
};

} // namespace adjd
#endif // SPECIALTHREAD_H
