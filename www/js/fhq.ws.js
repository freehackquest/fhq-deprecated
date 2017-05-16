if(!window.fhq) window.fhq = {};
if(!window.fhq.ws) window.fhq.ws = {};

// WebSocket protocol

window.fhq.ws.handlerReceivedChatMessage = function(response){
	fhq.handlerReceivedChatMessage(response);
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
	}else if(response.cmd == "chat"){
		fhq.ws.handlerReceivedChatMessage(response);
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
window.fhq.ws.onconnect = function(){};

window.fhq.ws.initWebsocket = function(){
	var protocol = window.location.protocol == "https:" ? "wss:" : "ws:";
	var port = window.location.protocol == "https:" ? "4613" : "1234";

	fhq.ws.socket = new WebSocket(protocol + "//" + window.location.hostname + ":" + port + "/");
	// fhq.ws.socket = new WebSocket("ws://192.168.1.5:1234/api");
	window.fhq.ws.socket.onopen = function() {
		console.log('WS Opened');
		window.fhq.ws.onconnect();
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

window.fhq.ws.getmap = function(params){
	params = params || {};
	params.cmd = 'getmap';
	return fhq.ws.send(params);
}

window.fhq.ws.sendChatMessage = function(params){
	params = params || {};
	params.cmd = 'sendchatmessage';
	return fhq.ws.send(params);
}

window.fhq.ws.sendMessageToAll = function(type, message){
	return fhq.ws.send({
		'cmd': 'sendmessagetoall',
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

fhq.ws.login = function(){
	return fhq.ws.send({
		'cmd': 'login',
		'token': fhq.getTokenFromCookie()
	});
}

fhq.ws.users = function(params){
	params = params || {};
	params.cmd = 'users';
	return fhq.ws.send(params);
}

fhq.ws.user = function(params){
	params = params || {};
	params.cmd = 'user';
	return fhq.ws.send(params);
}

fhq.ws.classbook = function(params){
	params = params || {};
	params.cmd = 'classbook';
	return fhq.ws.send(params);
}

fhq.ws.addhint = function(params){
	params = params || {};
	params.cmd = 'addhint';
	return fhq.ws.send(params);
}

fhq.ws.deletehint = function(params){
	params = params || {};
	params.cmd = 'deletehint';
	return fhq.ws.send(params);
}

fhq.ws.hints = function(params){
	params = params || {};
	params.cmd = 'hints';
	return fhq.ws.send(params);
}

fhq.ws.writeups = function(params){
	params = params || {};
	params.cmd = 'writeups';
	return fhq.ws.send(params);
}

fhq.ws.answerlist = function(params){
	params = params || {};
	params.cmd = 'answerlist';
	return fhq.ws.send(params);
}


fhq.ws.scoreboard = function(params){
	params = params || {};
	params.cmd = 'scoreboard';
	return fhq.ws.send(params);
}
