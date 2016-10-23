TEMPLATE = app
TARGET = freehackquestd

QT += core sql network websockets
QT -= gui

CONFIG   += console
CONFIG   -= app_bundle
OBJECTS_DIR = tmp/
MOC_DIR = tmp/
RCC_DIR = tmp/
CONFIG += c++11 c++14

SOURCES += \
	src/main.cpp \
	src/websocketserver.cpp \
	src/cmd_handlers/create_cmd_handlers.cpp \
	src/cmd_handlers/cmd_addnews_handler.cpp \
	src/cmd_handlers/cmd_getpublicinfo_handler.cpp \
	src/cmd_handlers/cmd_hello_handler.cpp \

HEADERS += \
	src/interfaces/iwebsocketserver.h \
	src/interfaces/icmdhandler.h \
	src/websocketserver.h \
	src/cmd_handlers/create_cmd_handlers.h \
	src/cmd_handlers/cmd_addnews_handler.h \
	src/cmd_handlers/cmd_getpublicinfo_handler.h \
	src/cmd_handlers/cmd_hello_handler.h \
	
