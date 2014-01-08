QT       += core
QT       -= gui
QT       += sql

TARGET = attack-defence-jury-daemon
CONFIG   += console
CONFIG   -= app_bundle

TEMPLATE = app

SOURCES +=  src/main.cpp \
            src/daemon.cpp

HEADERS +=  src/daemon.h
