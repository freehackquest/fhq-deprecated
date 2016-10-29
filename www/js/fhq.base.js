if(!window.fhq) window.fhq = {};

window.fhq.parsePageParams = function() {
	var loc = location.search.slice(1);
	var arr = loc.split("&");
	var result = {};
	var regex = new RegExp("(.*)=([^&#]*)");
	for(var i = 0; i < arr.length; i++){
		if(arr[i].trim() != ""){
			p = regex.exec(arr[i].trim());
			console.log("results: " + JSON.stringify(p));
			if(p == null)
				result[decodeURIComponent(arr[i].trim().replace(/\+/g, " "))] = '';
			else
				result[decodeURIComponent(p[1].replace(/\+/g, " "))] = decodeURIComponent(p[2].replace(/\+/g, " "));
		}
	}
	console.log(JSON.stringify(result));
	return result;
}

window.fhq.pageParams = fhq.parsePageParams();


window.fhq.containsPageParam = function(name){
	return (typeof fhq.pageParams[name] !== "undefined");
}

window.fhq.lang = function(){
	return window.fhq.sLang || window.fhq.locale();
}

window.fhq.locale = function(){
	var langs = ['en', 'ru']
	self.sLang = 'en';
	if(fhq.containsPageParam('lang') && langs.indexOf(this.pageParams['lang']) >= -1){
		self.sLang = fhq.pageParams['lang'];
	} else if (navigator) {
		var navLang = 'en';
		navLang = navigator.language ? navigator.language.substring(0,2) : navLang;
		navLang = navigator.browserLanguage ? navigator.browserLanguage.substring(0,2) : navLang;
		navLang = navigator.systemLanguage ? navigator.systemLanguage.substring(0,2) : navLang;
		navLang = navigator.userLanguage ? navigator.userLanguage.substring(0,2) : navLang;
		self.sLang =  langs.indexOf(navLang) >= -1 ? navLang : self.sLang;
	} else {
		self.sLang = 'en';
	}
	return self.sLang;
}

window.fhq.t = function(message){
	if(fhq.localization){
		if(fhq.localization[message]){
			return fhq.localization[message][fhq.lang()];
		}else{
			console.warn("Not found localization '" + message + "'");
		}
	}else{
		console.warn("Not found localization module '" + message + "'");
	}
	return message;
}

window.fhq.changeLocationState = function(newPageParams){
	var url = '';
	var params = [];
	console.log("changeLocationState");
	console.log("changeLocationState", newPageParams);
	for(var p in newPageParams){
		params.push(encodeURIComponent(p) + "=" + encodeURIComponent(newPageParams[p]));
	}
	console.log("changeLocationState", params);
	console.log("changeLocationState", window.location.pathname + '?' + params.join("&"));
	window.history.pushState(newPageParams, document.title, window.location.pathname + '?' + params.join("&"));
	fhq.pageParams = fhq.parsePageParams();
}
