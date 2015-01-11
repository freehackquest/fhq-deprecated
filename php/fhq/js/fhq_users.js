
// depricated
function showUserProfile(user_id) {

	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	};  
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			if(xmlhttp.responseText == "")
				document.getElementById("content_page").innerHTML = "content page don't found";
			else
			{
				showModalDialog(xmlhttp.responseText);
			}
		}
	}

	var url = "content_page.php?content_page=profile&user_id=" + user_id;
	xmlhttp.open("GET", url ,true);
	xmlhttp.send();	
}

function update_profile_location() {
	
	var params = {};
	params.country = document.getElementById('edit_user_country').value;
	params.city = document.getElementById('edit_user_city').value;
	params.university = document.getElementById('edit_user_university').value;
	var url = createUrlFromObj(params);
	send_request_post(
		'api/users/update_location.php',
		url,
		function (obj) {
			if (obj.result == "ok")
				showModalDialog("Saved. <br><br>");
			else
				showModalDialog("Fail. <br>" + obj.error.message + "<br><br>");
		}
	);
}

function update_profile_style() {
	
	var params = {};
	params.style = document.getElementById('edit_style').value;
	var url = createUrlFromObj(params);
	send_request_post(
		'api/users/update_style.php',
		url,
		function (obj) {
			if (obj.result == "ok")
				showModalDialog("Saved. Please reload page!!!<br><br>");
			else
				showModalDialog("Fail. <br>" + obj.error.message + "<br><br>");
		}
	);
}

function resetUsersPage() {
	document.getElementById('user_page').value = 0;
}

function setUsersPage(val) {
	document.getElementById('user_page').value = val;
}


function formEditUser() {
	alert("todo: formEditUser");
}

function createUserInfoRow(name, param) {
	return '<div class="user_info_row"><div class="user_info_param">' + name + '</div><div class="user_info_value">' + param + '</div></div>';
}

function createUserInfoRow_Skip() {
	return '<div class="user_info_row_skip"></div>';
}

function viewInfoUser(id) {
	showModalDialog('<div id="user_info"><center>Please wait...</div><div id="user_ips"></div>');
	

	var params = {};
	params.userid = id;

	// user info
	send_request_post(
		'api/users/get.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "fail") {
				document.getElementById('user_info').innerHTML = obj.error.message;
				return;
			}
			var ui = document.getElementById('user_info');
			var content = '<div class="user_info_table">';
			content += createUserInfoRow('Logo:', '<img src="'+ obj.data.logo + '"/>');
			content += createUserInfoRow('ID:', obj.data.userid);
			content += createUserInfoRow('E-mail:', obj.data.email);
			content += createUserInfoRow('Role:', obj.data.role);
			content += createUserInfoRow('Nick:', obj.data.nick);

			content += createUserInfoRow_Skip();

			for (var k in obj.profile) {
				content += createUserInfoRow('Profile "' + k + '":', obj.profile[k]);
			}

			content += createUserInfoRow_Skip();

			for (var k in obj.games) {
				content += createUserInfoRow('Game "' + obj.games[k].title + '" (' + obj.games[k].type_game + '):', obj.games[k].score);
			}
			content += createUserInfoRow_Skip();

			content += '</div>';
			ui.innerHTML = content;
			// ui.innerHTML += JSON.stringify(obj);
		}
	);
	
	// user_ips
	send_request_post(
		'api/users/get_ips.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "fail") {
				document.getElementById('user_ips').innerHTML = obj.error.message;
				return;
			}
			var ui = document.getElementById('user_ips');
			
			// .innerHTML = JSON.stringify(obj);

			var content = '<pre>';
			
			
			for (var k in obj.data) {
				content += obj.data[k].date + '\t'+ obj.data[k].ip + ' (' + obj.data[k].country + ', ' + obj.data[k].city + ')\n';
			}
			
			// content += JSON.stringify(obj);
			content += '</pre>';
			
			ui.innerHTML = content;
		}
	);
}

function updateUsers() {
	var lu = document.getElementById("listUsers");
	lu.innerHTML = "Please wait...";
	
	var params = {};
	params.search = document.getElementById('user_search').value;
	params.page = document.getElementById('user_page').value;
	
	var url = createUrlFromObj(params);
	send_request_post(
		'api/users/list.php',
		url,
		function (obj) {
			if (obj.result == "fail") {
				document.getElementById('error_search').innerHTML = obj.error.message;
				return;
			}
			
			var lu = document.getElementById("listUsers");
			lu.innerHTML = '';

			var found = parseInt(obj.found, 10);
			document.getElementById("search_found").innerHTML = found;

			var onpage = parseInt(obj.onpage, 10);
			var page = parseInt(obj.page, 10);
			
			var pages = Math.ceil(found / onpage);
			
			var pagesHtml = [];
			
			for (var i = 0; i < pages; i++) {
				if (i == page) {
					pagesHtml.push('<div class="selected_user_page">[' + (i+1) + ']</div>');
				} else {
					pagesHtml.push('<div class="button3 ad" onclick="setUsersPage(' + i + '); updateUsers();">[' + (i+1) + ']</div>');
				}
			}
			
			lu.innerHTML += pagesHtml.join(' ');
			
			
			var content = '<div class="users_table">';
			content += '<div class="users_row">';
			content += '	<div class="users_cell">Logo</div>';
			content += '	<div class="users_cell">ID</div>';
			content += '	<div class="users_cell">e-mail</div>';
			content += '	<div class="users_cell">nickname</div>';
			content += '	<div class="users_cell">role</div>';
			content += '	<div class="users_cell">status</div>';
			content += '</div>'; // users_row
			
			for (var k in obj.data) {
				content += '<div class="users_row">';

				// logo
				content += '<div class="users_cell users_cell_logo">';
				if (obj.data[k]['logo'] != null) 
					content += ' <img height="100px" src="' + obj.data[k]['logo'] + '"/>'
				else
					content += ' '
				content += '</div>'; // users_cell_logo

				// id
				content += '<div class="users_cell">' + k + '</div>';

				// email
				content += '<div class="users_cell">' + obj.data[k]['email'] + ' ';
				content += '	<div class="button3 ad" onclick="formRemoveUser(' + k + ');">Remove</div>'; // only check captcha
				content += '	<div class="button3 ad" onclick="viewInfoUser(' + k + ');">View Info</div>';
				content += '</div>';

				// nick
				// TODO: must be edit
				content += '<div class="users_cell">' + obj.data[k]['nick'] + '</div>';

				// role
				// TODO: must be edit
				content += '<div class="users_cell">' + obj.data[k]['role'] + '</div>';

				// status
				// TODO: if not activated can allow edit email and send mail again
				content += '<div class="users_cell">' + obj.data[k]['status'] + ' ';
				content += '	<div class="button3 ad" onclick="formBlockUser(' + k + ');">Block</div>';
				content += '</div>';

				content += '</div>'; // users_row
			}
			content += '</div>'; // users_table
			lu.innerHTML += content;
			// lu.innerHTML += JSON.stringify(obj);
		}
	);
}

function formCreateUser() {
	alert("todo: formCreateUser");
}

function createPageUsers() {
	var cp = document.getElementById('content_page');
	cp.innerHTML = '';
	cp.innerHTML += '<div class="button3 ad" onclick="formCreateUser();"> * Create User</div><br>';
	cp.innerHTML += '<div><input type="text" id="user_search" value="" onkeydown="if (event.keyCode == 13) {resetUsersPage(); updateUsers();};"/> <div class="button3 ad" onclick="resetUsersPage(); updateUsers();">Search</div> <input type="hidden" id="user_page" value="0"/> </div>';
	// cp.innerHTML += '<div>Page: <input type="text" value=""/></div>';
	cp.innerHTML += '<div>Found: <font id="search_found">0</font></div>';
	cp.innerHTML += '';
	cp.innerHTML += '<div id="error_search"></div>';
	cp.innerHTML += '<div id="listUsers"></div>';
}

