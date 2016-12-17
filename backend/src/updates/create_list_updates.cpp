#include "create_list_updates.h"
#include "update0067.h"

void create_list_updates(QVector<IUpdate *> &vUpdates){
	vUpdates.push_back(new Update0067());
}
