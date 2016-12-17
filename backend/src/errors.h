#ifndef ERRORS_H
#define ERRORS_H

#include <QString>
#include "error.h"

class Errors {
	public:
		static Error NotAuthorizedRequest();
		static Error AllowedOnlyForAdmin();
		static Error NotImplementedYet();
		static Error NotFoundUserByUUID(QString uuid);
		static Error NotFoundUUIDField();
		static Error LostDatabaseConnection();
	private:
		
};

#endif // ERRORS_H
