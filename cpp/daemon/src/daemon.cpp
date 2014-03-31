
#include "daemon.h"
#include <unistd.h>
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
#include "thread.h"

namespace adjd {
	
	void insertToFlags(adjd::db_conf &cnf, QString id_service, QString id_team_owner, QString flag, QString id_team_passed)
	{
		QSqlDatabase db = QSqlDatabase::addDatabase("QMYSQL");
		db.setHostName("localhost");
		db.setDatabaseName(cnf.db_name);
		db.setUserName(cnf.db_user);
		db.setPassword(cnf.db_pass);
		if (!db.open()){
			adjd::writeToLog(cnf, db.lastError().text());		
			adjd::writeToLog(cnf, "Failed to connect.");
			return;
		}

		QDateTime dateTime = QDateTime::currentDateTime();
		QString dateTimeString1 = dateTime.toString("yyyy-MM-dd hh:mm:ss");
			
		dateTime = dateTime.addSecs(600); // add 10 minutes. it is live of flag
		QString dateTimeString2 = dateTime.toString("yyyy-MM-dd hh:mm:ss");
		
		QSqlQuery query(db);
		QString strQuery = "INSERT INTO flags(id_service,flag,id_team_owner, dt_start, dt_end, id_team_passed) VALUES("
			+ id_service + ", "
			+ "'" + flag + "', "
			+ id_team_owner + ", "
			+ "'" + dateTimeString1 + "', "
			+ "'" + dateTimeString2 + "', "
			+ id_team_passed
		+ ");";
		// writeToLog(cnf, "strQuery = " + strQuery);
		query.exec(strQuery);
		db.close();
		return;
	}

	struct tables_ {
		QList<QMap<QString,QString> > services;
		QList<QMap<QString,QString> > teams;
	};

	bool getInfoTeamsAndServices(adjd::db_conf &cnf, adjd::tables_ &tabl)
	{
		QSqlDatabase db = QSqlDatabase::addDatabase("QMYSQL");
		db.setHostName("localhost");
		db.setDatabaseName(cnf.db_name);
		db.setUserName(cnf.db_user);
		db.setPassword(cnf.db_pass);
		if (!db.open()){
			adjd::writeToLog(cnf, db.lastError().text());		
			adjd::writeToLog(cnf, "Failed to connect.");
			return false;
		}

		// services
		{
			QSqlQuery query(db);
			query.exec("SELECT * FROM services");
			while (query.next()) {
				QSqlRecord record = query.record();
				QMap<QString,QString> map;
				for(int index = 0; index < record.count(); ++index) {
					QString key = record.fieldName(index);
					map.insert(key, record.value(index).toString());
				}
				tabl.services << map;
			}
		}
		
		// teams
		{
			QSqlQuery query(db);
			query.exec("SELECT * FROM teams");
			while (query.next()) {
				QSqlRecord record = query.record();
				QMap<QString,QString> map;
				for(int index = 0; index < record.count(); ++index) {
					QString key = record.fieldName(index);
					map.insert(key, record.value(index).toString());
				}
				tabl.teams << map;
			}
		}
		db.close();
		return true;
	}
	
	class RunChecker
	{
		public:
			RunChecker() {};
			
			RunChecker(
				adjd::db_conf &cnf, 
				QString service_scriptpath, 
				QString team_ip,
				QString team_id,
				QString team_name,
				QString service_id,
				QString service_name
				
			)
			{
				m_cnf = cnf;
				m_str_team_ip = team_ip;
				m_str_service_scriptpath = service_scriptpath;
				m_str_team_ip = team_ip;
				m_str_team_id = team_id;
				m_str_team_name = team_name;
				m_str_service_id = service_id;
				m_str_service_name = service_name;
				m_pProcess = new QProcess;
				m_pThread = new QThread;
			};
			
			~RunChecker() {
				delete m_pProcess;
				delete m_pThread;
			};
			
			void start() {
				 
				m_str_flag = QUuid::createUuid().toString();
				m_str_flag = m_str_flag.mid(1,m_str_flag.length()-2);
				QStringList args;
				args << m_str_team_ip << "store" << m_str_flag;
				
				m_startStamp = QTime::currentTime();
				m_pProcess->start(m_str_service_scriptpath, args);
				// m_pProcess->moveToThread(m_pThread);
			};
			
			bool isWorking()
			{
				QTime m_endStamp = QTime::currentTime();
				if( m_startStamp.secsTo(m_endStamp) > (90) ) {
					m_pProcess->kill();
					m_pProcess->terminate();
					m_pProcess->waitForFinished(100);
				};
				
				return (m_pProcess->state() == QProcess::Running || m_pProcess->state() == QProcess::Starting);
			}
			
			void writeResult()
			{
				if(isWorking()) return;
				int time = m_startStamp.secsTo(m_endStamp);
				
				QString p_stdout = m_pProcess->readAllStandardOutput();
				QString p_stderr = m_pProcess->readAllStandardError();
						
				if(p_stderr.length() > 0)
				{
					adjd::writeToLog(m_cnf, "[" + QString::number(time) + " secs] " + m_str_service_name + ":" + m_str_team_name + " Error in call checker: '" + m_str_service_scriptpath + "' p_stderr = " + p_stderr);
				}
				else
				{
					QString p_output = p_stdout.toUpper();
					QRegExp rx_work("\\[SERVICE IS WORK\\]");
					QRegExp rx_corrupt("\\[SERVICE IS CORRUPT\\]");

					if(rx_work.lastIndexIn(p_output) != -1)
					{
						insertToFlags(m_cnf, m_str_service_id, m_str_team_id, m_str_flag, "0");
						// adjd::writeToLog(cnf, service_name + ":" + team_name + " => service is work");
					}
					else if (rx_corrupt.lastIndexIn(p_output) != -1)
					{
						insertToFlags(m_cnf, m_str_service_id, m_str_team_id, m_str_flag, "-1");
						// adjd::writeToLog(cnf, service_name + ":" + team_name + " => service is corrupt");
					}
					else
					{
						adjd::writeToLog(m_cnf, "[" + QString::number(time) + " secs] " + m_str_service_name + ":" + m_str_team_name + " => (not found [service is work] or [service is corrupt] and you have only 3 sec),\np_stdout =\n" + p_stdout);
					}
				}
			}
			
		private:
		    QTime m_startStamp;
			QTime m_endStamp;
			adjd::db_conf m_cnf;
			
			QThread *m_pThread;
			QProcess *m_pProcess;
			QString m_str_flag;
			QString m_str_team_ip;
			QString m_str_team_id;
			QString m_str_team_name;
			QString m_str_service_id;
			QString m_str_service_name;
			QString m_str_service_scriptpath;
	};
	
	int attackDefenceJuryDaemon(adjd::db_conf &db_cnf)
	{
		QList<adjd::srvc_conf> user_services;
		
		// get list of user - service
		QSqlDatabase db = QSqlDatabase::addDatabase("QMYSQL");
		db.setHostName("localhost");
		db.setDatabaseName(db_cnf.db_name);
		db.setUserName(db_cnf.db_user);
		db.setPassword(db_cnf.db_pass);
		if (!db.open()){
			adjd::writeToLog(db_cnf, db.lastError().text());		
			adjd::writeToLog(db_cnf, "Failed to connect.");
			return false;
		}

		// services
		{
			QSqlQuery query(db);
			QString strQuery = "select iduser, ipserver, id as idservice, idgame, name, scriptpath from user, services where role='user'";
			query.exec(strQuery);
			while (query.next()) {
				QSqlRecord record = query.record();
				adjd::srvc_conf new_cnf;
				new_cnf.userID = record.value("iduser").toInt();
				new_cnf.serviceID = record.value("idservice").toInt();
				new_cnf.gameID = record.value("idgame").toInt();
				new_cnf.strUserIP = record.value("ipserver").toString();
				new_cnf.strServiceName = record.value("name").toString();
				new_cnf.strServiceScript = record.value("scriptpath").toString();
				user_services << new_cnf;
			}
		}
		db.close();
		QVector<ServiceCheckerThread *> threads;
		
		for(int i = 0; i < user_services.count(); i++)
		{
			adjd::srvc_conf srvs_cnf = user_services[i];
			QString strLog = "Start thread for: \n";
			strLog += "\tuserID: " + QString::number(srvs_cnf.userID) + ";\n";
			strLog += "\tserviceID: " + QString::number(srvs_cnf.serviceID) + ";\n";
			strLog += "\tgameID: " + QString::number(srvs_cnf.gameID) + ";\n";
			strLog += "\tstrUserIP: " + srvs_cnf.strUserIP + ";\n";
			strLog += "\tstrServiceName: " + srvs_cnf.strServiceName + ";\n";
			strLog += "\tstrServiceScript: " + srvs_cnf.strServiceScript + ";\n";			
			adjd::writeToLog(db_cnf, strLog);
			ServiceCheckerThread *thr = new ServiceCheckerThread(db_cnf, srvs_cnf);
			thr->start();
			threads.push_back(thr);
		}

		while(1) {
			adjd::writeToLog(db_cnf, "wait 5 minutes");
			SleepSimulator().sleep(30000);
			adjd::writeToLog(db_cnf, "end");
		}
		return 0;
/*
		QVector<RunChecker *> checkers;
		while(1) {
			for(int i = 0; i < 20; i++) 
			{
				QVector<RunChecker *> checkers2;
				for(int chi = 0; chi < checkers.size(); chi++)
				{
					if(!checkers[chi]->isWorking())
					{
						checkers[chi]->writeResult();
						delete checkers[chi];
					}
					else
						checkers2.push_back(checkers[chi]);
				}
				
				checkers.clear();
				for(int chi = 0; chi < checkers2.size(); chi++)
					checkers.push_back(checkers2[chi]);
				checkers2.clear();
				
				adjd::writeToLog(cnf, "run checkers");
				adjd::tables_ tabl;
				if( getInfoTeamsAndServices(cnf, tabl) )
				{
					for(int ti = 0; ti < tabl.teams.count(); ti++)
					{
						QString team_name = tabl.teams[ti]["name"];
						QString team_ip = tabl.teams[ti]["ip_address"];
						QString team_id = tabl.teams[ti]["id"];

						for(int si = 0; si < tabl.services.count(); si++)
						{
							QString service_id = tabl.services[si]["id"];
							QString service_name = tabl.services[si]["name"];
							QString service_scriptpath = tabl.services[si]["scriptpath"];

							if(QFile(service_scriptpath).exists())
							{
								RunChecker *checker = new RunChecker(
									cnf,
									service_scriptpath, 		
									team_ip, team_id, team_name,
									service_id, service_name
								);

								checker->start();
								checkers.push_back(checker);
								
								// checkers
								*/
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
									adjd::writeToLog(cnf, "Error in call checker: '" + service_scriptpath + "' p_stderr = " + p_stderr);
								}
								else
								{
									QString p_output = p_stdout.toUpper();
									QRegExp rx_work("\\[SERVICE IS WORK\\]");
									QRegExp rx_corrupt("\\[SERVICE IS CORRUPT\\]");

									if(rx_work.lastIndexIn(p_output) != -1)
									{
										insertToFlags(cnf, service_id, team_id, flag, "0");
										// adjd::writeToLog(cnf, service_name + ":" + team_name + " => service is work");
									}
									else if (rx_corrupt.lastIndexIn(p_output) != -1)
									{
										insertToFlags(cnf, service_id, team_id, flag, "-1");
										// adjd::writeToLog(cnf, service_name + ":" + team_name + " => service is corrupt");
									}
									else
									{
										adjd::writeToLog(cnf, service_name + ":" + team_name + " => (not found [service is work] or [service is corrupt] and you have only 3 sec),\np_stdout =\n" + p_stdout);
									}
								}
								* *//*
							}
							else
							{
								adjd::writeToLog(cnf, "team " + team_name + " (ip : " + team_ip + ") for service '" + service_name + "' script not found, script path: " + service_scriptpath);
							}
						}
					};
				};
				adjd::writeToLog(cnf, "wait 30 second");
				sleep(30);//wait 30 second
			}
			adjd::writeToLog(cnf, "wait 5 minutes");
			sleep(300);//wait 5 minutes
			
		}
		return 0;
	*/	
	}
}
