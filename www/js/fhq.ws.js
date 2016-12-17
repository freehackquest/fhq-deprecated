if(!window.fhq) window.fhq = {};
if(!window.fhq.ws) window.fhq.ws = {};

// WebSocket protocol

window.fhq.ws.handlerReceivedNews = function(response){
	fhq.handlerReceivedNews(response);
};
window.fhq.ws.listeners = {}
window.fhq.ws.addListener = function(cmd, d){
	if(!fhq.ws.listeners[cmd]){
		fhq.ws.listeners[cmd] = [];
	}
	fhq.ws.listeners[cmd].push(d);
}
fhq.ws.handleCommand = function(response){
	if(fhq.ws.listeners[response.cmd]){
		for(var d in fhq.ws.listeners[response.cmd]){
			if(response['error']){
				fhq.ws.listeners[response.cmd][d].reject(response);
			} else {
				fhq.ws.listeners[response.cmd][d].resolve(response);
			}
		}
		// clean listeners
		fhq.ws.listeners.hello = [];
	}else if(response.cmd == "news"){
		fhq.ws.handlerReceivedNews(response);
	}else{
		console.error("Not found handler for '" + response.cmd + "'");
	}
};

window.fhq.ws.WSState = "?";

window.fhq.ws.getWSState = function(){
	return fhq.ws.WSState;
}

window.fhq.ws.setWSState = function(s){
	fhq.ws.WSState = s;
	var el = document.getElementById('websocket_state');
	if(el){
		document.getElementById('websocket_state').innerHTML = s;
	}
}

window.fhq.ws.initWebsocket = function(){
	fhq.ws.socket = new WebSocket("ws://" + window.location.hostname + ":1234/");
	// fhq.ws.socket = new WebSocket("ws://freehackquest.com:1234/api");
	window.fhq.ws.socket.onopen = function() {
		console.log('WS Opened');
		fhq.ws.setWSState("OK");
		fhq.ws.send({'cmd': 'hello'}).done(function(){
			fhq.ws.login();
		});
	};

	window.fhq.ws.socket.onclose = function(event) {
		console.log('Closed');
		if (event.wasClean) {
			fhq.ws.setWSState("CLOSED");
		} else {
			fhq.ws.setWSState("BROKEN");
			setTimeout(function(){
				fhq.ws.setWSState("RECONN");
				fhq.ws.initWebsocket();
			}, 10000);
		  // Try reconnect after 5 sec
		}
		console.log('Code: ' + event.code + ' Reason: ' + event.reason);
	};
	fhq.ws.socket.onmessage = function(event) {
		console.log('Received: ' + event.data);
		try{
			var response = JSON.parse(event.data);
			fhq.ws.handleCommand(response);
		}catch(e){
			console.error(e);
		}
		
	};
	fhq.ws.socket.onerror = function(error) {
		console.log('Error: ' + error.message);
	};
}

fhq.ws.initWebsocket();

window.fhq.ws.send = function(obj, def){
	var d = def || $.Deferred();
	try{
		if(fhq.ws.socket.readyState == 0){
			setTimeout(function(){
				fhq.ws.send(obj, d);
			}, 1000);
		}else{
			// console.log("ReadyState " + fhq.ws.socket.readyState);
			console.log("Send " + JSON.stringify(obj));
			fhq.ws.socket.send(JSON.stringify(obj));
		}
	}catch(e){
		console.error(e);
	}
	fhq.ws.addListener(obj.cmd, d);
	return d;
}

window.fhq.ws.getPublicInfo = function(){
	return fhq.ws.send({
		'cmd': 'getpublicinfo'
	});
}

window.fhq.ws.addNews = function(type, message){
	return fhq.ws.send({
		'cmd': 'addnews',
		'type': type,
		'message': message
	});
}

window.fhq.ws.sendLettersToSubscribers = function(message){
	return fhq.ws.send({
		'cmd': 'send_letters_to_subscribers',
		'message': message
	});
}

window.fhq.ws.login = function(){
	return fhq.ws.send({
		'cmd': 'login',
		'token': fhq.getTokenFromCookie()
	});
}

window.fhq.ws.users = function(params){
	params = params || {};
	params.cmd = 'users';
	return fhq.ws.send(params);
}

window.fhq.ws.user = function(params){
	params = params || {};
	params.cmd = 'user';
	return fhq.ws.send(params);
}

window.fhq.ws.updatedatabase = function(params){
	params = params || {};
	params.cmd = 'updatedatabase';
	return fhq.ws.send(params);
}

window.fhq.ws.addhint = function(params){
	params = params || {};
	params.cmd = 'addhint';
	return fhq.ws.send(params);
}
