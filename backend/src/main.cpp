#include "src/qhttpserver/qhttpserver.h"
#include "src/qhttpserver/qhttprequest.h"
#include "src/qhttpserver/qhttpresponse.h"
#include <QVector>
#include <QFile>
#include <QSettings>
#include <iostream>
#include <QCoreApplication>
#include "handlermanager.h"
#include "handlers/auth_logon.h"
#include "database/databaseupdater.h"

int main(int argc, char* argv[]) {
	QCoreApplication app(argc, argv);

	std::cout << "For more information please use: --help\n";

	QVector<QString> m_args;
	for(int i = 0; i < argc; i++) {
		m_args.push_back(argv[i]);
	}

	if (m_args.contains("--help")) {
		std::cout << "Usage: \n\n"
			<< "\t" << argv[0] << " --help                - help\n"
			<< "\t" << argv[0] << " --daemon              - start daemon\n"
			<< "\t" << argv[0] << " --update-database     - update (or init) database structure\n"
			<< "\n\n ------ example file: /etc/fhq/backend/config.ini ------------- \n"
			<< "[database]\n"
			<< "host=localhost\n"
			<< "port=3306\n"
			<< "dbuser=fhq\n"
			<< "dbuserpassword=fhq\n"
			<< "dbname=fhq\n\n"
			<< "[server]\n"
			<< "port=8010\n"		
			<< "\n\n";
		return 1;
	}

	QString configFile = "/etc/fhq/backend/config.ini";
	QFile file(configFile);
	if (!file.exists()) {
		std::cout << "File: '" << configFile.toStdString() << "' not found. Please look --help \n";
		return -1;
	}

	HandlerManager *pHandlerManager = new HandlerManager();
	pHandlerManager->setConfigFile(configFile);

	if (m_args.contains("--update-database")) {
		DatabaseUpdater dbupdt;
		dbupdt.update(pHandlerManager->getSettings());
		return 0;
	}
	
	QHttpServer *pServer = new QHttpServer();
	pHandlerManager->RegisterHTTPHandler((IHTTPHandler *)new handlers::AuthLogon());
	pHandlerManager->setServer(pServer);

	// start server
	int nServerPort = pHandlerManager->getServerPort();
	std::cout << "Start server on " << nServerPort << " port.\n";
	pServer->listen(QHostAddress::Any, nServerPort);

	return app.exec();
}
