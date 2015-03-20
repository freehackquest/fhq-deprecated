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
		src/handlers/auth.cpp \
		src/database/databaseupdater.cpp \
		src/globalcontext.cpp \
		src/usersession.cpp \

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
	src/handlers/auth.h \
	src/database/databaseupdater.h \
	src/globalcontext.h \
	src/usersession.h \
	
	# src/daemon.h \
	# src/thread.h \
	# src/config.h 
