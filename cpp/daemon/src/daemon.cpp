
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

namespace adjd {
	
	void insertToFlags(adjd::config &cnf, QString id_service, QString id_team_owner, QString flag, QString id_team_passed)
	{
		QSqlDatabase db = QSqlDatabase::addDatabase("QMYSQL");
		db.setHostName("localhost");
		db.setDatabaseName(cnf.db_name);
		db.setUserName(cnf.db_user);
		db.setPassword(cnf.db_pass);
		if (!db.open()){
			writeToLog(cnf, db.lastError().text());		
			writeToLog(cnf, "Failed to connect.");
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
	
	void writeToLog(adjd::config &cnf, QString strMsg)
	{
		QFile file(cnf.strLogFile);
		if ( file.open(QIODevice::WriteOnly | QIODevice::Text | QIODevice::Append))
        {
			QDateTime dateTime = QDateTime::currentDateTime();
			QString dateTimeString = dateTime.toString("[yyyy-MMM-dd hh:mm:ss] ");

            QTextStream stream( &file );
            stream << dateTimeString << strMsg << endl;
            file.close();
        }
	}
	
	struct tables_ {
		QList<QMap<QString,QString> > services;
		QList<QMap<QString,QString> > teams;
	};

	bool getInfoTeamsAndServices(adjd::config &cnf, adjd::tables_ &tabl)
	{
		QSqlDatabase db = QSqlDatabase::addDatabase("QMYSQL");
		db.setHostName("localhost");
		db.setDatabaseName(cnf.db_name);
		db.setUserName(cnf.db_user);
		db.setPassword(cnf.db_pass);
		if (!db.open()){
			writeToLog(cnf, db.lastError().text());		
			writeToLog(cnf, "Failed to connect.");
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
				adjd::config &cnf, 
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
					writeToLog(m_cnf, "[" + QString::number(time) + " secs] " + m_str_service_name + ":" + m_str_team_name + " Error in call checker: '" + m_str_service_scriptpath + "' p_stderr = " + p_stderr);
				}
				else
				{
					QString p_output = p_stdout.toUpper();
					QRegExp rx_work("\\[SERVICE IS WORK\\]");
					QRegExp rx_corrupt("\\[SERVICE IS CORRUPT\\]");

					if(rx_work.lastIndexIn(p_output) != -1)
					{
						insertToFlags(m_cnf, m_str_service_id, m_str_team_id, m_str_flag, "0");
						// writeToLog(cnf, service_name + ":" + team_name + " => service is work");
					}
					else if (rx_corrupt.lastIndexIn(p_output) != -1)
					{
						insertToFlags(m_cnf, m_str_service_id, m_str_team_id, m_str_flag, "-1");
						// writeToLog(cnf, service_name + ":" + team_name + " => service is corrupt");
					}
					else
					{
						writeToLog(m_cnf, "[" + QString::number(time) + " secs] " + m_str_service_name + ":" + m_str_team_name + " => (not found [service is work] or [service is corrupt] and you have only 3 sec),\np_stdout =\n" + p_stdout);
					}
				}
			}
			
		private:
		    QTime m_startStamp;
			QTime m_endStamp;
			adjd::config m_cnf;
			
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
	
	int attackDefenceJuryDaemon(adjd::config &cnf)
	{
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
				
				writeToLog(cnf, "run checkers");
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
								* */
							}
							else
							{
								writeToLog(cnf, "team " + team_name + " (ip : " + team_ip + ") for service '" + service_name + "' script not found, script path: " + service_scriptpath);
							}
						}
					};
				};
				writeToLog(cnf, "wait 30 second");
				sleep(30);//wait 30 second
			}
			writeToLog(cnf, "wait 5 minutes");
			sleep(300);//wait 5 minutes
			
		}
		return 0;
		
	}
}
