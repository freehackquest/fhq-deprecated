#ifndef SLEEPSIMULATOR_H
#define SLEEPSIMULATOR_H
 
#include <QThread>
#include <QMutex>
#include <QWaitCondition>

class SleepSimulator {
     QMutex localMutex;
     QWaitCondition sleepSimulator;

public:
    SleepSimulator();
    void sleep(unsigned long sleepMS);
    void CancelSleep();
};

#endif // SLEEPSIMULATOR_H