if(!window.fhq) window.fhq = {};
if(!window.fhq.ws) window.fhq.ws = {};

fhq.ws.protocol = window.location.protocol == "https:" ? "wss:" : "ws:";
fhq.ws.port = window.location.protocol == "https:" ? "4613" : "1234";
fhq.ws.hostname = window.location.hostname;
