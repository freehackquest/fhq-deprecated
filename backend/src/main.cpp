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
			<< "\t" << argv[0] << " --help" << std::endl
      << "\t\t just this help" << std::endl

			/*<< "\t" << argv[0] << " --daemon" << std::endl
      << "\t\t not work now! run as daemon\n"*/

  		<< "\t" << argv[0] << " --example-config\n"
      << "\t\t show example config file (/etc/fhq/backend/config.ini)\n"

			<< "\t" << argv[0] << " --update-database\n"
      << "\t\t update (or init) database structure\n"

  		/*<< "\t" << argv[0] << " --create-database-by-root <root_password>"
      << "\t\t not work now! create empty database with default name, user and password \n"*/
			<< "\n\n";
		return 1;
	}

  if (m_args.contains("--example-config")) {
    std::cout << GlobalContext::getExampleConfigFile().toStdString()
      << "\n\n";
    return 2;
  }

  // check exists config file
	QString configFile = "/etc/fhq/backend/config.ini";
	QFile file(configFile);
	if (!file.exists()) {
		std::cout << "File: '" << configFile.toStdString() << "' not found. Please look --help \n";
		return -1;
	}

  GlobalContext *globalContext = new GlobalContext(configFile);

	HandlerManager *pHandlerManager = new HandlerManager(globalContext);
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
