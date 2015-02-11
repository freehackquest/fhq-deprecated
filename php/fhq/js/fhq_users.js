
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

function changeUserLogo(userid) {
	var params = {};
	params.userid = userid;
	params.logo = document.getElementById('user_new_logo').value;
	send_request_post(
		'api/users/update_logo.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "fail") {
				alert(obj.error.message);
				return;
			}
			document.getElementById('user_current_logo').src = obj.data.logo;
		}
	);
}

function changeUserNick(userid) {
	var params = {};
	params.userid = userid;
	params.nick = document.getElementById('user_new_nick').value;
	send_request_post(
		'api/users/update_nick.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "fail") {
				alert(obj.error.message);
				return;
			}
			document.getElementById('user_current_nick').innerHTML = obj.data.nick;
		}
	);
}

function changeUserPassword(userid) {
	var params = {};
	params.userid = userid;
	params.password = document.getElementById('user_new_password').value;
	params.email = document.getElementById('user_current_email').innerHTML;
	send_request_post(
		'api/users/update_password.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "fail") {
				alert(obj.error.message);
				return;
			}
			document.getElementById('user_new_password').value = "";
			alert('updated');
		}
	);
}

function changeUserStatus(userid) {
	var params = {};
	params.userid = userid;
	params.status = document.getElementById('user_new_status').value;
	send_request_post(
		'api/users/update_status.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "fail") {
				alert(obj.error.message);
				return;
			}
			document.getElementById('user_current_status').innerHTML = obj.data.status;
		}
	);
}

function changeUserRole(userid) {
	var params = {};
	params.userid = userid;
	params.role = document.getElementById('user_new_role').value;
	send_request_post(
		'api/users/update_role.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "fail") {
				alert(obj.error.message);
				return;
			}
			document.getElementById('user_current_role').innerHTML = obj.data.role;
		}
	);
}

function deleteUser(userid) {
	if (!confirm("Are you sure that wand remove user?"))
		return;

	var params = {};
	params.userid = userid;
	send_request_post(
		'api/users/delete.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "fail") {
				alert(obj.error.message);
				return;
			}
			alert("removed! Cry baby cry...");
			closeModalDialog();
			updateUsers();
		}
	);
}

function createUserInfoRow(name, param) {
	return '<div class="user_info_row"><div class="user_info_param">' + name + '</div><div class="user_info_value">' + param + '</div></div>';
}

function createUserInfoRow_Skip() {
	return '<div class="user_info_row_skip"></div>';
}

function showUserInfo(id) {
	showModalDialog('<div id="user_info"><center>Please wait...</div>');

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
			content += createUserInfoRow('Logo:', '<img id="user_current_logo" src="'+ obj.data.logo + '"/>');
			content += createUserInfoRow('ID:',  obj.data.userid);
			content += createUserInfoRow('E-mail:', '<div id="user_current_email">' + obj.data.email + '</div>');
			content += createUserInfoRow('Role:', '<div id="user_current_role">' + obj.data.role + '</div>');
			content += createUserInfoRow('Nick:', '<div id="user_current_nick">' + obj.data.nick + '</div>');
			content += createUserInfoRow('Status:', '<div id="user_current_status">' + obj.data.status + '</div>');
			
			content += createUserInfoRow_Skip();
			
			content += createUserInfoRow('Change Logo:', '<input id="user_new_logo" type="text" value="' + obj.data.logo + '" > <div class="button3 ad" onclick="changeUserLogo(' + obj.data.userid + ');">Save</div> ');
			content += createUserInfoRow('Change Nick:', '<input id="user_new_nick" type="text" value="' + obj.data.nick + '" > <div class="button3 ad" onclick="changeUserNick(' + obj.data.userid + ');">Save</div> ');
			content += createUserInfoRow('Change Password:', '<input id="user_new_password" type="password" value="" > <div class="button3 ad" onclick="changeUserPassword(' + obj.data.userid + ');">Save</div> ');
			content += createUserInfoRow('Change Status:', '<input id="user_new_status" type="text" value="' + obj.data.status + '" > <div class="button3 ad" onclick="changeUserStatus(' + obj.data.userid + ');">Save</div> ');
			content += createUserInfoRow('Change Role:', '<input id="user_new_role" type="text" value="' + obj.data.role + '" > <div class="button3 ad" onclick="changeUserRole(' + obj.data.userid + ');">Save</div> ');
			content += createUserInfoRow('Remove User:', '<div class="button3 ad" onclick="deleteUser(' + obj.data.userid + ');">Remove</div> ');

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
}

function showUserIP(id) {
	showModalDialog('<div id="user_ips"></div>');

	var params = {};
	params.userid = id;
	document.getElementById('user_ips').innerHTML = "Please wait...";
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
				content += obj.data[k].date + '\t'+ obj.data[k].ip + ' (' + obj.data[k].country + ', ' + obj.data[k].city + ') \t' + obj.data[k].browser + '\n';
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
	params.role = document.getElementById('user_role').value;
	params.status = document.getElementById('user_status').value;
	params.onpage = document.getElementById('user_onpage').value;

	// alert(createUrlFromObj(params));
	send_request_post(
		'api/users/list.php',
		createUrlFromObj(params),
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
			content += '	<div class="users_cell">E-mail</div>';
			content += '	<div class="users_cell">Nick</div>';
			content += '	<div class="users_cell">Role</div>';
			content += '	<div class="users_cell">Status</div>';
			content += '	<div class="users_cell">Last Sign in</div>';
			content += '</div>'; // users_row
			
			for (var k in obj.data) {
        var userinfo = obj.data[k];

				content += '<div class="users_row">';

				// logo
				content += '<div class="users_cell users_cell_logo">';
				if (userinfo.logo != null) 
					content += ' <img height="100px" src="' + userinfo.logo + '"/>'
				else
					content += ' '
				content += '</div>'; // users_cell_logo

				// id
				content += '<div class="users_cell">' + userinfo.userid + '</div>';

				// email
				content += '<div class="users_cell"> ' + userinfo.email;
				content += '	<div class="button3 ad" onclick="showUserInfo(' + userinfo.userid + ');">Info</div>';
				content += '	<div class="button3 ad" onclick="showUserIP(' + userinfo.userid + ');">IP</div>';
				content += '</div>';
				content += '<div class="users_cell">' + userinfo.nick + '</div>';
				content += '<div class="users_cell">' + userinfo.role + '</div>';

				// status
				// TODO: if not activated can allow edit email and send mail again 
				content += '<div class="users_cell">' + userinfo.status + '</div> ';

				content += '<div class="users_cell">' + userinfo.date_last_signup + '</div>';				
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

	var content = '<div class="user_info_table">';
	content += createUserInfoRow('', '<div class="button3 ad" onclick="formCreateUser();"> * Create User</div>');
	content += createUserInfoRow_Skip();
	
	content += createUserInfoRow('E-mail or Nick:', '<input type="text" id="user_search" value="" onkeydown="if (event.keyCode == 13) {resetUsersPage(); updateUsers();};"/>');
	
	var user_role = ' <select id="user_role">';
	user_role += '	<option value="">*</option>';
	user_role += '	<option value="user">User</option>';
	user_role += '	<option value="tester">Tester</option>';
	user_role += '	<option value="admin">Admin</option>';
	user_role += '</select> ';
	content += createUserInfoRow('Role:', user_role);
	
	var user_status = ' <select id="user_status">';
	user_status += '	<option value="">*</option>';
	user_status += '	<option value="blocked">Blocked</option>';
	user_status += '	<option value="activated">Activated</option>';
	user_status += '	<option value="notactivated">Not Activated</option>';
	user_status += '</select> ';
	content += createUserInfoRow('Status:', user_status);
	
	var user_onpage = ' <select id="user_onpage">';
	user_onpage += '	<option value="5">5</option>';
	user_onpage += '	<option value="10">10</option>';
	user_onpage += '	<option value="15">15</option>';
	user_onpage += '	<option value="20">20</option>';
	user_onpage += '	<option value="30">30</option>';
	user_onpage += '	<option value="50">50</option>';
	user_onpage += '</select> ';
	content += createUserInfoRow('On Page:', user_onpage);
	
	
	content += createUserInfoRow('', '<div class="button3 ad" onclick="resetUsersPage(); updateUsers();">Search</div>');
	content += createUserInfoRow_Skip();
	content += createUserInfoRow('Found:', '<font id="search_found">0</font>');
	
	content += '</div>'; // user_info_table
	content += '<input type="hidden" id="user_page" value="0"/>'	
	cp.innerHTML += content;
	cp.innerHTML += '<div id="error_search"></div>';
	cp.innerHTML += '<hr/>';
	cp.innerHTML += '<div id="listUsers"></div>';
}

