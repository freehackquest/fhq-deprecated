#include "src/qhttpserver/qhttpserver.h"
#include "src/qhttpserver/qhttprequest.h"
#include "src/qhttpserver/qhttpresponse.h"
#include <QVector>
#include <QFile>
#include <QSettings>
#include <iostream>
#include <QCoreApplication>
#include "handlermanager.h"
#include "handlers/auth.h"
#include "handlers/games.h"
#include "handlers/services.h"
#include "handlers/teams.h"
#include "database/databaseupdater.h"

int main(int argc, char* argv[]) {
	QCoreApplication app(argc, argv);

	QVector<QString> m_args;
	for(int i = 0; i < argc; i++) {
		m_args.push_back(argv[i]);
	}

	if (m_args.contains("--example-config")) {
		std::cout << GlobalContext::getExampleConfigFile().toStdString()
			<< "\n\n";
		return 2;
	}
	
	std::cout << "For more information please use: --help\n";
	
	if (m_args.contains("--help")) {
		std::cout << "Usage: \n\n"
			<< "\t" << argv[0] << " --help" << std::endl
			<< "\t\t just this help" << std::endl << std::endl

			<< "\t" << argv[0] << " --example-config\n"
			<< "\t\t show example config file\n\t\t'/etc/fhq/backend/config.ini'" << std::endl << std::endl

			<< "\t" << argv[0] << " --update-database\n"
			<< "\t\t update (or init) database structure" << std::endl << std::endl

			<< "\t" << argv[0] << " --server" << std::endl
			<< "\t\t run your server" << std::endl << std::endl
			
			<< "\t" << argv[0] << " --create-database" << std::endl
			<< "\t\t not work now! create empty database with name,\n\t\t user and password from config\n"
			<< "\n\n";
		return 1;
	}

	

	// check exists config file
	QString configFile = "/etc/fhq/backend/config.ini";

	if (!GlobalContext::checkConfigFile(configFile)) {
		return -1;
	}

	GlobalContext *pGlobalContext = new GlobalContext(configFile);

	if (m_args.contains("--update-database")) {
		DatabaseUpdater dbupdt;
		dbupdt.update(pGlobalContext);
		return 0;
	} else if (m_args.contains("--create-database")) {
		DatabaseUpdater dbupdt;
		dbupdt.create(pGlobalContext);
		return 0;
	} else if (m_args.contains("--server")) {
		HandlerManager *pHandlerManager = new HandlerManager(pGlobalContext);
		pHandlerManager->RegisterHTTPHandler((IHTTPHandler *)new handlers::AuthLogon());
		pHandlerManager->RegisterHTTPHandler((IHTTPHandler *)new handlers::AuthLogoff());
		
		pHandlerManager->RegisterHTTPHandler((IHTTPHandler *)new handlers::TeamsList());
		pHandlerManager->RegisterHTTPHandler((IHTTPHandler *)new handlers::TeamsInsert());
		pHandlerManager->RegisterHTTPHandler((IHTTPHandler *)new handlers::TeamsUpdate());
		pHandlerManager->RegisterHTTPHandler((IHTTPHandler *)new handlers::TeamsDelete());
		
		/*pHandlerManager->RegisterHTTPHandler((IHTTPHandler *)new handlers::ServicesList());
		pHandlerManager->RegisterHTTPHandler((IHTTPHandler *)new handlers::ServicesInsert());
		pHandlerManager->RegisterHTTPHandler((IHTTPHandler *)new handlers::ServicesUpdate());
		pHandlerManager->RegisterHTTPHandler((IHTTPHandler *)new handlers::ServicesDelete());
		
		pHandlerManager->RegisterHTTPHandler((IHTTPHandler *)new handlers::GamesList());
		pHandlerManager->RegisterHTTPHandler((IHTTPHandler *)new handlers::GamesInsert());
		pHandlerManager->RegisterHTTPHandler((IHTTPHandler *)new handlers::GamesUpdate());
		pHandlerManager->RegisterHTTPHandler((IHTTPHandler *)new handlers::GamesDelete());*/
		pHandlerManager->startServer();
		return app.exec();
	}

	return 0;
}
