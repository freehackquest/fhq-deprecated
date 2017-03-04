#include "create_list_updates.h"
#include "update0067.h"
#include "update0068.h"
#include "update0069.h"
#include "update0070.h"

void create_list_updates(QVector<IUpdate *> &vUpdates){
	vUpdates.push_back(new Update0067());
	vUpdates.push_back(new Update0068());
	vUpdates.push_back(new Update0069());
	vUpdates.push_back(new Update0070());
}
