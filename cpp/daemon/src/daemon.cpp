
#include "daemon.h"
#include <unistd.h>
#include <QString>
#include <QFile>
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
		
		return true;
	}
	
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
		return;
	}
	
	int attackDefenceJuryDaemon(adjd::config &cnf)
	{
		while(1) {
			for(int i = 0; i < 20; i++) 
			{
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
							QString flag = QUuid::createUuid().toString();
							flag = flag.mid(1,flag.length()-2);

							if(QFile(service_scriptpath).exists())
							{
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
							}
							else
							{
								writeToLog(cnf, "team " + team_name + " (ip : " + team_ip + ") for service '" + service_name + "' script not found, script path: " + service_scriptpath);
							}
						}
					};
				};
				sleep(15);//wait 15 secund
			}
			sleep(300);//wait 5 minutes
		}
		return 0;
		
	}
}
