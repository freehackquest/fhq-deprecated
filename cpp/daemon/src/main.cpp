#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <signal.h>
#include <iostream>
#include <sys/stat.h>
#include <sys/types.h>
#include <sys/time.h>
#include <unistd.h>
#include <errno.h>
#include <fcntl.h>
#include <syslog.h>
#include <QtCore>
#include <QString>
#include "daemon.h"

adjd::config cnf;
 
/**
  a signal handler for the Linux signals sent to daemon process,
  for more signals, refer to http://www.comptechdoc.org/os/linux/programming/linux_pgsignals.html
  */
  
void signal_handler(int sig)
{
	switch(sig) {
		case SIGHUP:
			writeToLog(cnf, "hangup signal catched");
		break;
		case SIGTERM:
			writeToLog(cnf, "terminate signal catched");
		break;
	}
}
 
 
/**
  create background process out of the application, source code taken from: http://www.enderunix.org/docs/eng/daemon.php
  with some minor modifications
  */
  
 /*
void init_daemon()
{
	int i,lfp;
	char str[10];
	if(getppid()==1)
	return; // already a daemon
	i=fork();
	if (i<0)
	exit(1); // fork error
	if (i>0)
	exit(0); // parent exits
	 
	// child (daemon) continues
	setsid(); // obtain a new process group
	 
	for (i=getdtablesize();i>=0;--i)
	close(i); // close all descriptors
	i=open("/dev/null",O_RDWR); dup(i); dup(i); // handle standart I/O 
	 
	umask(027); // set newly created file permissions
	 
	chdir(RUNNING_DIR); // change running directory
	lfp=open(LOCK_FILE,O_RDWR|O_CREAT,0640);
	if (lfp<0)
	exit(1); // can not open
	if (lockf(lfp,F_TLOCK,0)<0)
	exit(0); // can not lock
	// first instance continues
	sprintf(str,"%d\n",getpid());
	write(lfp,str,strlen(str)); // record pid to lockfile
	signal(SIGCHLD,SIG_IGN); // ignore child
	signal(SIGTSTP,SIG_IGN); // ignore tty signals
	signal(SIGTTOU,SIG_IGN);
	signal(SIGTTIN,SIG_IGN);
	signal(SIGHUP,signal_handler); // catch hangup signal
	signal(SIGTERM,signal_handler); // catch kill signal
}
*/

int main(int argc, char* argv[]) {

	if(argc < 2 )
	{
		std::cout << "Usage: " << argv[0] << " <logfile-fullpath> \n\n";
		return -1;
	}

	cnf.strLogFile = QString(argv[1]);
	cnf.db_name = "jury";
	cnf.db_user = "jury";
	cnf.db_pass = "jury";

	writeToLog(cnf, "Daemon Start");

    pid_t parpid, sid;
    
    parpid = fork(); // создаем дочерний процесс
    if(parpid < 0) {
        exit(1);
    } else if(parpid != 0) {
        exit(0);
    } 
    umask(0);// даем права на работу с фс
    sid = setsid();// генерируем уникальный индекс процесса
    if(sid < 0) {
        exit(1);
    }
    if((chdir("/")) < 0) {
		//выходим в корень фс
        exit(1);
    }
    close(STDIN_FILENO);//закрываем доступ к стандартным потокам ввода-вывода
    close(STDOUT_FILENO);
    close(STDERR_FILENO);
    
    return adjd::attackDefenceJuryDaemon(cnf);
}
