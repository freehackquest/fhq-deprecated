if(!window.fhq) window.fhq = {};
if(!window.fhq.ui) window.fhq.ui = {};

window.fhq.ui.createCopyright = function() {
	$("body").append(''
		+ '<div id="copyright">'
		+ '	<center>'
		+ '		<font face="Arial" size=2>Copyright © 2011-2016 sea-kg. | '
		+ '		<a href="http://freehackquest.com/?about">About</a> | '
		+ '		WS State: <font id="websocket_state">?</font>'
		+ '	</center>'
		+ '</div>'
	);
}

$(document).ready(function() {
	fhq.ui.createCopyright();
});
