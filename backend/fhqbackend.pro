QT	+= core
QT	-= gui
QT	+= sql
QT	+= network

TARGET = fhqbackend
CONFIG   += console
CONFIG   -= app_bundle

TEMPLATE = app
OBJECTS_DIR = tmp/
MOC_DIR = tmp/

SOURCES +=  \
		src/main.cpp \
		src/qhttpserver/qhttpconnection.cpp \
		src/qhttpserver/qhttpserver.cpp \
		src/qhttpserver/qhttprequest.cpp \
		src/qhttpserver/qhttpresponse.cpp \
		src/http_parser/http_parser.c \
		src/handlermanager.cpp \
		src/handlers/auth_logon.cpp \
		src/database/databaseupdater.cpp \
		src/SecretToken.cpp \

HEADERS += \
	src/qhttpserver/qhttpconnection.h \
	src/qhttpserver/qhttpserver.h \
	src/qhttpserver/qhttprequest.h \
	src/qhttpserver/qhttpresponse.h \
	src/qhttpserver/qhttpserverapi.h \
	src/qhttpserver/qhttpserverfwd.h \
	src/http_parser/http_parser.h \
	src/handlermanager.h \
	src/ihttphandler.h \
	src/handlers/auth_logon.h \
	src/database/databaseupdater.h \
	src/SecretToken.h \
	
	# src/daemon.h \
	# src/thread.h \
	# src/config.h 
