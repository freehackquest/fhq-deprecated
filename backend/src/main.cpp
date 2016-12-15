#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <signal.h>
#include <iostream>
#include <sys/stat.h>
#include <sys/types.h>
#include <sys/time.h>
#include <unistd.h>
#include <errno.h>
#include <fcntl.h>
#include <syslog.h>
#include <QtCore>
#include <QFile>
#include <QString>
#include "websocketserver.h"
#include "cmd_handlers/create_cmd_handlers.h"

int main(int argc, char** argv) {
	QCoreApplication a(argc, argv);

    QCommandLineParser parser;
    parser.setApplicationDescription("freehackquestd");
    parser.addHelpOption();

    QCommandLineOption dbgOption(QStringList() << "d" << "debug", QCoreApplication::translate("main", "Debug output [default: off]."));
    parser.addOption(dbgOption);
    
    QCommandLineOption exportApiOption(QStringList() << "e" << "export-api", QCoreApplication::translate("main", "Export API"));
    parser.addOption(exportApiOption);
    
    QCommandLineOption portOption(QStringList() << "p" << "port",
            QCoreApplication::translate("main", "Port for freehackquestd [default: 1234]."),
            QCoreApplication::translate("main", "port"), QLatin1Literal("1234"));
    parser.addOption(portOption);
    parser.process(a);
    
    
    bool exportApi = parser.isSet(exportApiOption);
    
    if(exportApi){
		qDebug().nospace().noquote() << "# FreeHackQuest API (WebSocket port 1234)\n";
		QMap<QString, ICmdHandler *> pHandlers;
		create_cmd_handlers(pHandlers);
		foreach( QString key, pHandlers.keys()){
			ICmdHandler *pHandler = pHandlers.value(key);
			qDebug().nospace().noquote() << "## Command `" << pHandler->cmd() << "`\n";
			
			qDebug().nospace().noquote() << "### Access\n";
			qDebug().nospace().noquote() << " * " << (pHandler->accessUnauthorized() ? "Allowed" : "Denied") << " access unauthorized requests";
			qDebug().nospace().noquote() << " * " << (pHandler->accessUser() ? "Allowed" : "Denied") << " access user requests";
			qDebug().nospace().noquote() << " * " << (pHandler->accessTester() ? "Allowed" : "Denied") << " access tester requests";
			qDebug().nospace().noquote() << " * " << (pHandler->accessAdmin() ? "Allowed" : "Denied") << " access admin requests";
			qDebug().nospace().noquote() << "\n";
		}
		return 0;
	}
    
    bool debug = parser.isSet(dbgOption);
    int port = parser.value(portOption).toInt();

	if(!QFile::exists("/etc/freehackquestd/conf.ini")){
		qDebug() << "Not found /etc/freehackquestd/conf.ini";
		return 0;
	}

    WebSocketServer *server = new WebSocketServer(port, debug);
    QObject::connect(server, &WebSocketServer::closed, &a, &QCoreApplication::quit);
    
	return a.exec();
}
