#include "errors.h"

Error Errors::NotAuthorizedRequest(){
	Error error(1001, "Not Authorized Request");
	return error;
}

Error Errors::AllowedOnlyForAdmin(){
	Error error(1002, "Allowed only for admin");
	return error;
}

Error Errors::NotImplementedYet(){
	Error error(1003, "Not implemented yet");
	return error;
}

Error Errors::NotFoundUserByUUID(QString uuid){
	Error error(1004, "Not found user by uuid " + uuid);
	return error;
}

Error Errors::NotFoundUUIDField(){
	Error error(1005, "Not found uuid field");
	return error;
}

Error Errors::LostDatabaseConnection(){
	Error error(1006, "Lost Database Connection");
	return error;
}
