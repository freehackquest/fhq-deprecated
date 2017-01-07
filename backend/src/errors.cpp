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

Error Errors::QuestIDMustBeInteger(){
	Error error(1007, "Parameter 'questid' must be integer");
	return error;
}

Error Errors::QuestIDMustBeNotZero(){
	Error error(1008, "Parameter 'questid' must be not zero");
	return error;
}

Error Errors::HintIDMustBeInteger(){
	Error error(1009, "Parameter 'hintid' must be integer");
	return error;
}

Error Errors::HintIDMustBeNotZero(){
	Error error(1010, "Parameter 'hintid' must be not zero");
	return error;
}
