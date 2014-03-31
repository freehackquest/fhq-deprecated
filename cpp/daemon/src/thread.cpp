#include "thread.h"
#include <unistd.h>
#include <QtCore>
#include <QString>
#include <QFile>
#include <QVector>
#include <QThread>
#include <QTextStream>
#include <QMap>
#include <QList>
#include <QtSql/QSqlDatabase>
#include <QtSql/QSqlError>
#include <QSqlQuery>
#include <QSqlRecord>
#include <QProcess>
#include <QUuid>
#include <QRegExp>
 
namespace adjd {
 
void writeToLog(adjd::db_conf &cnf, QString strMsg)
{
	g_mutexLog.lock();
	QFile file(cnf.strLogFile);
	if ( file.open(QIODevice::WriteOnly | QIODevice::Text | QIODevice::Append))
	{
		QDateTime dateTime = QDateTime::currentDateTime();
		QString dateTimeString = dateTime.toString("[yyyy-MMM-dd hh:mm:ss] ");
		QTextStream stream( &file );
		stream << dateTimeString << strMsg << endl;
		file.close();
	}
	g_mutexLog.unlock();
}
 
ServiceCheckerThread::ServiceCheckerThread(const adjd::db_conf &db_cnf, const adjd::srvc_conf &srvs_cnf)
{
	this->db_cnf = db_cnf;
	this->srvs_cnf = srvs_cnf;
	
   // pTestObject=0;
}
 
/*void ServiceCheckerThread::setTestClass(TestClass *pTO) {
    pTestObject=pTO;
}*/
 
void ServiceCheckerThread::run() {
	while(1) {
		QString strLog = "Start thread for: ";
			strLog += "-" + QString::number(srvs_cnf.userID);
			strLog += "-" + QString::number(srvs_cnf.serviceID);
			strLog += "-" + QString::number(srvs_cnf.gameID) + ";";
			adjd::writeToLog(db_cnf, strLog);
		SleepSimulator().sleep(2000);
	}
	
	/*
	QStringList args;
	args << team_ip << "store" << flag;
	QProcess p;
	p.start(service_scriptpath, args);
	p.waitForFinished(3000);

	QString p_stdout = p.readAllStandardOutput();
	QString p_stderr = p.readAllStandardError();
	
	if(p_stderr.length() > 0)
	{
		writeToLog(cnf, "Error in call checker: '" + service_scriptpath + "' p_stderr = " + p_stderr);
	}
	else
	{
		QString p_output = p_stdout.toUpper();
		QRegExp rx_work("\\[SERVICE IS WORK\\]");
		QRegExp rx_corrupt("\\[SERVICE IS CORRUPT\\]");

		if(rx_work.lastIndexIn(p_output) != -1)
		{
			insertToFlags(cnf, service_id, team_id, flag, "0");
			// writeToLog(cnf, service_name + ":" + team_name + " => service is work");
		}
		else if (rx_corrupt.lastIndexIn(p_output) != -1)
		{
			insertToFlags(cnf, service_id, team_id, flag, "-1");
			// writeToLog(cnf, service_name + ":" + team_name + " => service is corrupt");
		}
		else
		{
			writeToLog(cnf, service_name + ":" + team_name + " => (not found [service is work] or [service is corrupt] and you have only 3 sec),\np_stdout =\n" + p_stdout);
		}
	}
	*/
}


SleepSimulator::SleepSimulator()
{
	localMutex.lock();
}

void SleepSimulator::sleep(unsigned long sleepMS)
{
	sleepSimulator.wait(&localMutex, sleepMS);
}

void SleepSimulator::CancelSleep()
{
	sleepSimulator.wakeAll();
}

} // namespace adjd
