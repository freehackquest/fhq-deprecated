
var g_userRoles = [
		{ type: 'user', caption: 'User'},
		{ type: 'tester',  caption: 'Tester'},
		{ type: 'admin',  caption: 'Admin'}
	];

var g_userStatus = [
		{ type: 'activated', caption: 'Activated'},
		{ type: 'blocked',  caption: 'Blocked'}
	];

// the same function createComboBoxGame
function createComboBoxUser(idelem, value, arr) {
	var result = '<select id="' + idelem + '">';
	for (var k in arr) {
		result += '<option ';
		if (arr[k].type == value)
			result += ' selected ';
		result += ' value="' + arr[k].type + '">';
		result += arr[k].caption + '</option>';
	}
	result += '</select>';
	return result;
}

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
	if (userid != null) {
		params.userid = userid;
	}
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
			if (userid == null) {
				document.getElementById('btn_user_info').innerHTML = obj.data.nick;
			}
		}
	);
}

// for admin
// TODO rename to update_user_password
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

// for user
function userpage_changeUserPassword() {
	var params = {};
	params.old_password = document.getElementById('userpage_old_password').value;
	params.new_password = document.getElementById('userpage_new_password').value;
	params.new_password_confirm = document.getElementById('userpage_new_password_confirm').value;
	send_request_post(
		'api/users/change_password.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "fail") {
				showModalDialog(obj.error.message);
				return;
			}
			document.getElementById('userpage_old_password').value = "";
			document.getElementById('userpage_new_password').value = "";
			document.getElementById('userpage_new_password_confirm').value = "";
			showModalDialog('updated');
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
	return '<div class="user_info_row"><div class="user_info_param">' + name + '</div><div class="user_info_value">' + param + '</div></div>\n';
}

function createUserInfoRow_Skip() {
	return '<div class="user_info_row_skip"></div>\n';
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
			if (obj.access.edit == true) {
				content += createUserInfoRow('E-mail:', '<div id="user_current_email">' + obj.data.email + '</div>');
				content += createUserInfoRow('Role:', '<div id="user_current_role">' + obj.data.role + '</div>');
			}
			content += createUserInfoRow('Nick:', '<div id="user_current_nick">' + obj.data.nick + '</div>');
			if (obj.access.edit == true)
				content += createUserInfoRow('Status:', '<div id="user_current_status">' + obj.data.status + '</div>');
			
			if (obj.access.edit) {
				if (!obj.currentUser) {
					content += createUserInfoRow_Skip();
					content += createUserInfoRow('Change Logo:', '<input id="user_new_logo" type="text" value="' + obj.data.logo + '" > <div class="button3 ad" onclick="changeUserLogo(' + obj.data.userid + ');">Save</div>');
					content += createUserInfoRow('Change Nick:', '<input id="user_new_nick" type="text" value="' + obj.data.nick + '" > <div class="button3 ad" onclick="changeUserNick(' + obj.data.userid + ');">Save</div> ');
					content += createUserInfoRow('Change Password:', '<input id="user_new_password" type="password" value="" > <div class="button3 ad" onclick="changeUserPassword(' + obj.data.userid + ');">Save</div> ');
					content += createUserInfoRow('Change Status:', createComboBoxUser('user_new_status', obj.data.status, g_userStatus) + ' <div class="button3 ad" onclick="changeUserStatus(' + obj.data.userid + ');">Save</div> ');
					content += createUserInfoRow('Change Role:', createComboBoxUser('user_new_role', obj.data.role, g_userRoles)  + '<div class="button3 ad" onclick="changeUserRole(' + obj.data.userid + ');">Save</div> ');
					content += createUserInfoRow('Remove User:', '<div class="button3 ad" onclick="deleteUser(' + obj.data.userid + ');">Remove</div> ');
				}

				content += createUserInfoRow_Skip();
				for (var k in obj.profile) {
					content += createUserInfoRow('Profile "' + k + '":', obj.profile[k]);
				}
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

function getHTMLPaging1(min,max,onpage,page) {
	if (min == max || page > max || page < min )
		return " Paging Error ";
	
	var pages = Math.ceil(max / onpage);

	var pagesInt = [];
	var leftp = 5;
	var rightp = leftp + 1;
	
	
	
	if (pages > (leftp + rightp + 2)) {
		pagesInt.push(min);
		if (page - leftp > min + 1) {
			pagesInt.push(-1);
			for (var i = (page - leftp); i <= page; i++) {
				pagesInt.push(i);
			}
		} else {
			for (var i = min+1; i <= page; i++) {
				pagesInt.push(i);
			}
		}
		
		if (page + rightp < pages-1) {
			for (var i = page+1; i < (page + rightp); i++) {
				pagesInt.push(i);
			}
			pagesInt.push(-1);
		} else {
			for (var i = page+1; i < pages-1; i++) {
				pagesInt.push(i);
			}
		}
		if (page != pages-1)
			pagesInt.push(pages-1);
	} else {
		for (var i = 0; i < pages; i++) {
			pagesInt.push(i);
		}
	}

	var pagesHtml = [];
	for (var i = 0; i < pagesInt.length; i++) {
		if (pagesInt[i] == -1) {
			pagesHtml.push("...");
		} else if (pagesInt[i] == page) {
			pagesHtml.push('<div class="selected_user_page">[' + (pagesInt[i]+1) + ']</div>');
		} else {
			pagesHtml.push('<div class="button3 ad" onclick="setUsersPage(' + pagesInt[i] + '); updateUsers();">[' + (pagesInt[i]+1) + ']</div>');
		}
	}

	return pagesHtml.join(' ');
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

			// var pages = Math.ceil(found / onpage);
			
			/*var pagesHtml = [];
			
			for (var i = 0; i < pages; i++) {
				if (i == page) {
					pagesHtml.push('<div class="selected_user_page">[' + (i+1) + ']</div>');
				} else {
					pagesHtml.push('<div class="button3 ad" onclick="setUsersPage(' + i + '); updateUsers();">[' + (i+1) + ']</div>');
				}
			}*/
			
			lu.innerHTML += '<div id="user_paging">' + getHTMLPaging1(0,found, onpage, page) + '</div>';
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

function createUser() {
	var params = {};
	params.uuid = document.getElementById('newuser_uuid').value;
	params.logo = document.getElementById('newuser_logo').value;
	params.email = document.getElementById('newuser_email').value;
	params.role = document.getElementById('newuser_role').value;
	params.nick = document.getElementById('newuser_nick').value;
	params.password = document.getElementById('newuser_password').value;
	params.status = document.getElementById('newuser_status').value;

	// alert(createUrlFromObj(params));
	send_request_post(
		'api/users/insert.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "fail") {
				document.getElementById('newuser_errors').innerHTML = obj.error.message;
				return;
			}
			closeModalDialog();
			updateUsers();
		}
	);
}

function formCreateUser() {
	
	var content = '<div class="user_info_table">';
	content += createUserInfoRow('Uuid:', '<input type="text" id="newuser_uuid" value="' + guid() + '"/>');
	content += createUserInfoRow('Logo:', '<input type="text" id="newuser_logo" value="files/users/0.png"/>');
	content += createUserInfoRow('E-mail:', '<input type="text" id="newuser_email" value=""/>');

	
	content += createUserInfoRow('Role:', createComboBoxUser('newuser_role', 'user', g_userRoles));
	content += createUserInfoRow('Nick:', '<input type="text" id="newuser_nick" value=""/>');
	content += createUserInfoRow('Password:', '<input type="password" id="newuser_password" value=""/>');

	content += createUserInfoRow('Status:', createComboBoxUser('newuser_status', 'activated', g_userStatus));
	content += createUserInfoRow('', '<div class="button3 ad" onclick="createUser();">Create</div>');
	
	content += createUserInfoRow('', '<div id="newuser_errors"></div>');
	
	content += createUserInfoRow_Skip();
	content += '</div>';
	showModalDialog(content);
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
	user_status += '</select> ';
	content += createUserInfoRow('Status:', user_status);
	
	var user_onpage = ' <select id="user_onpage">';
	user_onpage += '	<option value="5">5</option>';
	user_onpage += '	<option value="10">10</option>';
	user_onpage += '	<option selected value="15">15</option>';
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

function getComboBoxStyle(idelem, currentstyle) {
	
	var templates = [];
	templates.push({style: 'base', caption: 'Base'});
	templates.push({style: 'dark', caption: 'Nigth'});
	templates.push({style: 'yellow', caption: 'Yellow (not completed)'});
	templates.push({style: 'red', caption: 'Red (not completed)'});
	
	var result = '<select id="' + idelem + '">';
	for (var k in templates) {
		result += '<option ';
		if (currentstyle == templates[k].style)
			result += ' selected ';
		result += ' value="' + templates[k].style + '">' + templates[k].caption + '</option>';
	}
	result += '</select>';
	return result;
}

function updateUserLogo(userid) {
	var files = document.getElementById('user_new_logo').files;
	/*for(i = 0; i < files.length; i++)
		alert(files[i].name);*/
	
	send_request_post_files(
		files,
		'api/users/upload_logo.php',
		createUrlFromObj({"userid": userid}),
		function (obj) {
			if (obj.result == "fail") {
				showModalDialog(obj.error.message);
				return;
			}
			document.getElementById('user_logo').src = obj.data.logo + '?' + new Date().getTime();
			showModalDialog('updated');
		}
	);
}

function loadUserProfile(userid) {
	// alert(userid);

	var cp = document.getElementById('content_page');
	cp.innerHTML = 'Please wait...';

	// alert(createUrlFromObj(params));
	send_request_post(
		'api/users/get.php',
		createUrlFromObj({"userid": userid}),
		function (obj) {
			// alert(1);
			if (obj.result == "fail") {
				content = obj.error.message;
				cp.innerHTML = content;
				return;
			}
			var content = '<div class="user_info_table">';
			content += createUserInfoRow('ID:', userid);
	
			content += createUserInfoRow('Your logo:', '<img id="user_logo" src="' + obj.data.logo + '"/>');
			content += createUserInfoRow('Your name:', '<div id="user_current_nick">' + obj.data.nick + '</div>');
			content += createUserInfoRow('Your role:', obj.data.role);
			for (var k in obj.games) {
				content += createUserInfoRow('Game "' + obj.games[k].title + '" (' + obj.games[k].type_game + '):', obj.games[k].score);
			}
			content += createUserInfoRow_Skip();
			content += createUserInfoRow('Update logo:', 'PNG: <input id="user_new_logo" type="file" accept="image/png" required/>');
			content += createUserInfoRow('', '<div class="button3 ad" onclick="updateUserLogo(' + userid + ');">Upload</div>');
			
			content += createUserInfoRow_Skip();
			content += createUserInfoRow('Update nick:', '<input id="user_new_nick" type="text" value="' + obj.data.nick + '"/>');
			content += createUserInfoRow('', '<div class="button3 ad" onclick="changeUserNick(null);">Change name</div>');
			content += createUserInfoRow_Skip();
			content += createUserInfoRow('Country:', '<input id="edit_user_country" type="text" value="'+obj.profile.country+'"/>');
			content += createUserInfoRow('City:', '<input id="edit_user_city" type="text" value="'+obj.profile.city+'"/>');
			content += createUserInfoRow('University:', '<input id="edit_user_university" type="text" value="'+obj.profile.university+'"/>');
			content += createUserInfoRow('', '<div class="button3 ad" onclick="update_profile_location();">Update</div>');
			content += createUserInfoRow_Skip();

			// todo change password
			content += createUserInfoRow('Old password:', '<input id="userpage_old_password" type="password" value=""/>');
			content += createUserInfoRow('New password:', '<input id="userpage_new_password" type="password" value=""/>');
			content += createUserInfoRow('New password(confirm):', '<input id="userpage_new_password_confirm" type="password" value=""/>');
			content += createUserInfoRow('', '<div class="button3 ad" onclick="userpage_changeUserPassword();">Change password</div>');
			content += createUserInfoRow_Skip();

			// todo style
			content += createUserInfoRow('Сolor spectrum', getComboBoxStyle('edit_style', obj.profile.template));
			content += createUserInfoRow('', '<div class="button3 ad" onclick="update_profile_style()">Save</div>');

			content += '</div>'; // user_info_table
			cp.innerHTML = content;
		}
	);
}
