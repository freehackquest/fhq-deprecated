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
		QString strLog = "thread id ";
		strLog += " " + QString::number(srvs_cnf.userID);
		strLog += "-" + QString::number(srvs_cnf.serviceID);
		strLog += "-" + QString::number(srvs_cnf.gameID) + ";";
		adjd::writeToLog(db_cnf, strLog);
	
		QString flag = QUuid::createUuid().toString();
		flag = flag.mid(1,flag.length()-2);
		
		if (srvs_cnf.strUserIP.isEmpty()) {
			adjd::writeToLog(db_cnf, "FAIL: UserIP is empty!!!");
			return;
		}
		
		QFile file(srvs_cnf.strServiceScript);
		if (!file.exists()) {
			adjd::writeToLog(db_cnf, "FAIL: Script Path to Checker not found");
			return;
		}

		QStringList args;
		args << srvs_cnf.strUserIP << "store" << flag;
		
		QProcess p;
		p.start(srvs_cnf.strServiceScript, args);
		p.waitForFinished(10000);
		p.kill();
		
		QString p_stdout = p.readAllStandardOutput();
		QString p_stderr = p.readAllStandardError();
		
		if(p_stderr.length() > 0)
		{
			writeToLog(db_cnf, "Error in call checker: '" + srvs_cnf.strServiceScript + "' p_stderr = " + p_stderr);
		} else {
			QString p_output = p_stdout.toUpper();
			QRegExp rx_work("\\[SERVICE IS WORKED\\]");
			QRegExp rx_corrupt("\\[SERVICE IS CORRUPT\\]");

			if(rx_work.lastIndexIn(p_output) != -1)
			{
				updateFlags(flag, true);
				// writeToLog(db_cnf, srvs_cnf.strServiceName + ":" + QString::number(srvs_cnf.userID) + " => service is worked");
			}
			else if (rx_corrupt.lastIndexIn(p_output) != -1)
			{
				updateFlags(flag, false);
				// insertToFlags(cnf, service_id, team_id, flag, "-1");
				// writeToLog(db_cnf, srvs_cnf.strServiceName + ":" + QString::number(srvs_cnf.userID) + " => service is corrupt");
			}
			else
			{
				updateFlags(flag, false);
				writeToLog(db_cnf, srvs_cnf.strServiceName + ":" + QString::number(srvs_cnf.userID) + " => (not found [service is worked] or [service is corrupt] and you have only 10 sec),\np_stdout =\n" + p_stdout);
			}
		}
		SleepSimulator().sleep(10000);
	}
}

void ServiceCheckerThread::updateFlags(QString flag, bool bWorked)
{
	QSqlDatabase db = QSqlDatabase::addDatabase("QMYSQL");
	db.setHostName("localhost");
	db.setDatabaseName(db_cnf.db_name);
	db.setUserName(db_cnf.db_user);
	db.setPassword(db_cnf.db_pass);
	if (!db.open()){
		adjd::writeToLog(db_cnf, db.lastError().text());		
		adjd::writeToLog(db_cnf, "Failed to connect.");
		return;
	}

	QDateTime dateTime = QDateTime::currentDateTime();
	QString dateTimeString1 = dateTime.toString("yyyy-MM-dd hh:mm:ss");
		
	dateTime = dateTime.addSecs(600); // add 10 minutes. it is live of flag
	QString dateTimeString2 = dateTime.toString("yyyy-MM-dd hh:mm:ss");
	
	{
		QSqlQuery query(db);
		QString strQuery = "INSERT INTO flags_live(idservice, flag, owner, date_start, date_end, user_passed) VALUES("
			+ QString::number(srvs_cnf.serviceID) + ", "
			+ "'" + flag + "', "
			+ QString::number(srvs_cnf.userID) + ", "
			+ "'" + dateTimeString1 + "', "
			+ "'" + dateTimeString2 + "', "
			+ "0"
		+ ");";
		// adjd::writeToLog(db_cnf, strQuery);
		query.exec(strQuery);
		// adjd::writeToLog(db_cnf, "OK");
	}
	
	int cnt = 0;
	
	{
		QString strQuery = "select count(*) as cnt from scoreboard where owner = " + QString::number(srvs_cnf.userID) + " and name = \"" + srvs_cnf.strServiceName + "\" and idgame = " + QString::number(srvs_cnf.gameID);
		QSqlQuery query(db);
		// adjd::writeToLog(db_cnf, strQuery);
		query.exec(strQuery);
		// adjd::writeToLog(db_cnf, "OK");
		if(query.next()) {
			QSqlRecord record = query.record();
			cnt = record.value("cnt").toInt();
		}
	}
	
	if (cnt == 1) {
		QString strQuery = "UPDATE scoreboard SET "
		" score = " + (bWorked ? QString("1") : QString("0")) + ", "
		+ " date_change = '" + dateTimeString1 + "' "
		+ " WHERE idgame = " + QString::number(srvs_cnf.gameID) + " "
		+ " and name = \"" + srvs_cnf.strServiceName + "\" "
		+ " and owner = " + QString::number(srvs_cnf.userID) + ""
		+ ";";
		adjd::writeToLog(db_cnf, strQuery);
		QSqlQuery query(db);
		query.exec(strQuery);
		adjd::writeToLog(db_cnf, "OK");
	}

	// moving from flags_live to flags
	{
		QString strQuery = "select id from flags_live where "
			" owner = " + QString::number(srvs_cnf.userID) + " "
			" and idservice = \"" + QString::number(srvs_cnf.serviceID) + "\" "
			" and (date_end < NOW() or user_passed > 0)";
		QSqlQuery query(db);
		adjd::writeToLog(db_cnf, strQuery);
		query.exec(strQuery);
		adjd::writeToLog(db_cnf, "OK");
		while(query.next()) {
			QSqlRecord record = query.record();
			int id = record.value("id").toInt();
			QString strQueryInsert = "INSERT flags(idservice, flag, owner, date_start, date_end, user_passed) "
				" SELECT idservice, flag, owner, date_start, date_end, user_passed "
				" FROM flags_live WHERE id = " + QString::number(id);
			
			QSqlQuery query_insert(db);
			// adjd::writeToLog(db_cnf, strQueryInsert);
			query_insert.exec(strQueryInsert);
			// adjd::writeToLog(db_cnf, "OK");
			
			QSqlQuery query_delete(db);
			QString strQueryDelete = "DELETE FROM flags_live WHERE id = " + QString::number(id);
			// adjd::writeToLog(db_cnf, strQueryDelete);
			query_delete.exec(strQueryDelete);
			// adjd::writeToLog(db_cnf, "OK");
		}
	}
	
	if (bWorked) {
		// update scoreboard defence
		{
			QString strId = QString::number(srvs_cnf.userID);
			QString strQuery = "update scoreboard set date_change = NOW(), score = (select count(*) from flags where owner = " + strId + " and user_passed = 0)  where owner = " + strId + " and name = 'Defence';";
			QSqlQuery query(db);
			query.exec(strQuery);
		}

		// update scoreboard offence
		{
			QString strId = QString::number(srvs_cnf.userID);
			QString strQuery = "update scoreboard set date_change = NOW(), score = (select count(*) from flags where owner <> " + strId + " and user_passed = " + strId + ")  where owner = " + strId + " and name = 'Offence';";
			QSqlQuery query(db);
			query.exec(strQuery);
		}

		// update summary
		{
			QString strUserId = QString::number(srvs_cnf.userID);
			QString strGameId = QString::number(srvs_cnf.gameID);
			
			QString strQuerySelect = "select ifnull(sum(score),0) as sm from scoreboard where name <> 'Summary' and idgame = " + strGameId + " and owner = " + strUserId + " ";
			QSqlQuery query_select(db);
			query_select.exec(strQuerySelect);
			int score = 0;
			if (query_select.next()) {
				QSqlRecord record = query_select.record();
				score = record.value("sm").toInt();
			}

			QString strQueryUpdate = "update scoreboard set date_change = NOW(), score = " + QString::number(score) + " where owner = " + strUserId + " and name = 'Summary' and idgame = " + strGameId;
			QSqlQuery query_update(db);
			query_update.exec(strQueryUpdate);
		}
	}
	db.close();
	return;
}
// ---------------------------------------------------------------------	

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
