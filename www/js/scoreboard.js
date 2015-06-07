
function updateInfo(gameid) {
	
	var params = {};
	params['gameid'] = gameid;
	send_request_post(
		'api/games/get.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				document.getElementById('game_info_panel').innerHTML = '';
				document.getElementById('game_info_panel').innerHTML += obj.data.title;
				document.getElementById('game_info_panel').innerHTML += ' (' + obj.data.type_game + ')';
			} else {
				document.getElementById('game_info_panel').innerHTML = obj.error.message;
			}
		}
	);

	// init scoreboard
	var tblscore = document.getElementById('scoreboard_table');
	var content = '';
	content = '<div class="fhq_scoreboard_row">';
	content += '<div class="fhq_scoreboard_cell">Place</div>';
	content += '<div class="fhq_scoreboard_cell">Score</div>';
	content += '<div class="fhq_scoreboard_cell">Users</div>';
	content += '</div>'; // row
	tblscore.innerHTML += content;
	updateScoreboard(gameid);
	initNewsPanel();
	updateNews();
}

var maxPlace = 0;

function getElementByPlace(place, score) {
	// var el = ;
	if (maxPlace < place) {
		maxPlace = place;
	}
	var el = document.getElementById('place' + place);
	if (!el) {
		content = '<div class="fhq_scoreboard_row">';
		content += '<div class="fhq_scoreboard_cell">' + place + '</div>';
		content += '<div id="score' + place + '" class="fhq_scoreboard_cell">' + score + '</div>';
		content += '<div id="place' + place + '" class="fhq_scoreboard_cell"></div>';
		content += '</div>'; // row
		document.getElementById('scoreboard_table').innerHTML += content;
		el = document.getElementById('place' + place);
	} else {
		var elscore = document.getElementById('score' + place);
		elscore.innerHTML = score;
	}
	return el;
}

function updateScoreboard(gameid) {
	var params = {};
	params["gameid"] = gameid;
	send_request_post(
		'api/games/scoreboard.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == 'ok') {
				var lastPlace = 0;
				for (var k in obj.data) {
					content = '<div class="fhq_scoreboard_row">';
					if (obj.data.hasOwnProperty(k)) {
						var place = k;
						var score = obj.data[k][0].score;
						var el = getElementByPlace(place, score);

						var arr = [];
						for (var k2 in obj.data[k]) {
							arr.push(' <div class="fhqmiddelouter"> <img class="fhqmiddelinner" width=25px src="' + obj.data[k][k2].logo + '"/> ' + obj.data[k][k2].nick + ' </div> ');
						}
						var users = arr.join(' &nbsp;&nbsp;&nbsp;&nbsp; ');
						el.innerHTML = users;
					}
					lastPlace = k;
				}
				lastPlace = parseInt(lastPlace,10);
				maxPlace = parseInt(maxPlace,10);
				for (var i = lastPlace+1; i <= maxPlace; i++) {
					var el = getElementByPlace(i, 0);
					el.innerHTML = "";
				}

			} else {
				el.innerHTML = obj.error.message;
			}
			setTimeout(function(){ updateScoreboard(gameid); }, 2000);
		}
	);	
}

var g_maxRows = 15;

function initNewsPanel() {
	document.getElementById("events_panel").innerHTML = '';
	for(var i = 0; i < g_maxRows; i++) {
		document.getElementById("events_panel").innerHTML += '<div id="news' + i + '"></div><br>';
	}
}

var audio_event = new Audio('files/sound/event.mp3');

function pushNews(text) {
	// move to down one rows
	for(var i = g_maxRows-1; i > 0; i--) {
		var el1 = document.getElementById("news" + i);
		var el2 = document.getElementById("news" + (i-1));
		el1.innerHTML = el2.innerHTML;
	}
	document.getElementById("news0").innerHTML = text;
	
	if (audio_event.paused)
		audio_event.play();
	// alert(1);
};

var lastNewsID = 0;

function updateNews() {
	var params = {};
	if (lastNewsID > 0) {
		params['id'] = lastNewsID;
	};

	send_request_post(
		'api/events/list.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "fail") {
				// el.innerHTML = obj.error.message;
			} else {
				lastNewsID = obj.data.maxid;
				// el.innerHTML += lastNewsID + '<br>';
				var arr = [];
				for (var k in obj.data.events) {
					if (obj.data.events.hasOwnProperty(k)) {
						var e = obj.data.events[k];
						// arr.push('[' + e.type + ', ' + e.dt + ']<br>' + e.message);
						arr.push(fhqgui.eventView(e, false));
					}
				}
				arr = arr.reverse();
				for( var i = 0; i < arr.length; i++) {
					pushNews(arr[i]);
				};
			}
			setTimeout(function(){ updateNews(); }, 2000);
		}
	);
}
