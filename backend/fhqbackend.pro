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
	src/handlers/auth/auth_logon.cpp \
	src/handlers/auth/auth_logoff.cpp \
	src/handlers/teams.cpp \
	src/handlers/games/games_delete.cpp \
	src/handlers/games/games_insert.cpp \
	src/handlers/games/games_list.cpp \
	src/handlers/games/games_update.cpp \
	src/handlers/games/games_updatelogo.cpp \
	src/handlers/services.cpp \
	src/handlers/admin/admin_changepassword.cpp \
	src/handlers/admin/admin_gamestart.cpp \
	src/handlers/admin/admin_gamestop.cpp \
	src/handlers/admin/admin_scoreboardfreeze.cpp \
	src/handlers/admin/admin_userdelete.cpp \
	src/handlers/admin/admin_userinsert.cpp \
	src/handlers/admin/admin_userupdate.cpp \
	src/handlers/users.cpp \
	src/handlers/scoreboard.cpp \
	src/database/databaseupdater.cpp \
	src/globalcontext.cpp \
	src/usersession.cpp \
	src/generatedocumentation.cpp \


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
	src/handlers/teams.h \
	src/handlers/games.h \
	src/handlers/services.h \
	src/handlers/users.h \
	src/handlers/scoreboard.h \
	src/database/databaseupdater.h \
	src/globalcontext.h \
	src/usersession.h \
	src/generatedocumentation.h \
