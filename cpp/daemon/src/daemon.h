#ifndef DAEMON_H
#define DAEMON_H

#include <QtCore>
#include <QString>
#include "config.h"

namespace adjd {

	int attackDefenceJuryDaemon(db_conf &cnf);
	
}; // namespace adjd

#endif
