#include <unistd.h>
#include <QtCore>
#include <QString>
#include <QFile>
#include <QVector>
#include <QThread>
#include <QTextStream>
#include <QMap>
#include <QList>
#include <QtSql/QSqlDatabase>
#include <QtSql/QSqlError>
#include <QSqlQuery>
#include <QSqlRecord>
#include <QProcess>
#include <QUuid>
#include <QRegExp>

#include "sleepsimulator.h"

SleepSimulator::SleepSimulator()
{
	localMutex.lock();
}

void SleepSimulator::sleep(unsigned long sleepMS)
{
	sleepSimulator.wait(&localMutex, sleepMS);
}

void SleepSimulator::CancelSleep()
{
	sleepSimulator.wakeAll();
}
