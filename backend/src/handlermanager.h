#ifndef HANDLERMANAGER
#define HANDLERMANAGER

#include "qhttpserver/qhttpserverfwd.h"
#include "ihttphandler.h"
#include <QObject>
#include <QMap>
#include <QJsonObject>
#include <QSettings>

/// HelloWorld
class HandlerManager : public QObject
{
		Q_OBJECT
		QMap<QString, IHTTPHandler*> m_mapHandlers;
    GlobalContext *m_pGlobalContext;
    QHttpServer *m_pServer;
	public:
		HandlerManager(GlobalContext *pGlobalContext);
		void startServer();

		void RegisterHTTPHandler(IHTTPHandler*);
		void UnregisterHTTPHandler(IHTTPHandler*);

	public slots:
		void handleRequest(QHttpRequest *req, QHttpResponse *resp);		
};

#endif // HANDLERMANAGER
