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

	if (!GlobalContext::checkConfigFile(configFile)) {
		return -1;
	}

  GlobalContext *pGlobalContext = new GlobalContext(configFile);
	HandlerManager *pHandlerManager = new HandlerManager(pGlobalContext);

	if (m_args.contains("--update-database")) {
		DatabaseUpdater dbupdt;
		dbupdt.update(pGlobalContext);
		return 0;
	}
	
  // list of handlers
  pHandlerManager->RegisterHTTPHandler((IHTTPHandler *)new handlers::AuthLogon());
  pHandlerManager->RegisterHTTPHandler((IHTTPHandler *)new handlers::AuthLogoff());
	pHandlerManager->startServer(pServer);
 
  
	return app.exec();
}
