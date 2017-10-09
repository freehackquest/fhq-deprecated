
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

function showUserInfo(userid) {
	showModalDialog('<div id="user_info"><center>Please wait...</div>');

	// user info
	fhq.ws.user({userid: userid}).done(function (obj) {
			var ui = document.getElementById('user_info');
			
			
			var pt = new FHQParamTable();
			pt.row('Logo:', '<img id="user_current_logo" src="'+ obj.data.logo + '"/>');
			pt.row('ID:',  obj.data.userid);
			if (obj.access.edit == true) {
				pt.row('E-mail:', '<div id="user_current_email">' + obj.data.email + '</div>');
				pt.row('Role:', '<div id="user_current_role">' + obj.data.role + '</div>');
			}
			pt.row('Nick:', '<div id="user_current_nick">' + obj.data.nick + '</div>');
			if (obj.access.edit == true){
				pt.row('Status:', '<div id="user_current_status">' + obj.data.status + '</div>');
			}

			if (obj.access.edit) {
				if (!obj.currentUser) {
					pt.skip();

					pt.row('Change Logo:', '<input id="user_new_logo" type="text" value="' + obj.data.logo + '" > '
						+ fhqgui.btn('Save', 'changeUserLogo(' + obj.data.userid + ');'));

					pt.row('Change Nick:', '<input id="user_new_nick" type="text" value="' + obj.data.nick + '" > '
						+ fhqgui.btn('Save', 'changeUserNick(' + obj.data.userid + ');'));

					pt.row('Change Password:', '<input id="user_new_password" type="password" value="" > '
						+ fhqgui.btn('Save', 'changeUserPassword(' + obj.data.userid + ');'));

					pt.row('Change Status:', fhqgui.combobox('user_new_status', obj.data.status, fhq.getUserStatuses()) + ' '
						+ fhqgui.btn('Save', 'changeUserStatus(' + obj.data.userid + ');'));
					pt.row('Change Role:', fhqgui.combobox('user_new_role', obj.data.role, fhq.getUserRoles()) + ' '
						+ fhqgui.btn('Save', 'changeUserRole(' + obj.data.userid + ');'));
					pt.row('Remove User:', fhqgui.btn('Remove', 'deleteUser(' + obj.data.userid + ');'));
				}

				pt.skip();
				for (var k in obj.profile) {
					pt.row('Profile "' + k + '":', obj.profile[k]);
				}
			}
			pt.skip();
			for (var k in obj.games) {
				pt.row('Game "' + obj.games[k].title + '" (' + obj.games[k].type_game + '):', obj.games[k].score);
			}
			pt.skip();
			pt.row('', fhqgui.btn('Open in New Tab', 'fhqgui.openUserInNewTab(' + userid + ');'));
			pt.skip();
			ui.innerHTML = pt.render();
			// ui.innerHTML += JSON.stringify(obj);

		}
	).fail(function(r){
		document.getElementById('user_info').innerHTML = obj.error.message;
	});
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
			
			var ud = document.getElementById("usersdump");
			ud.innerHTML = '';
			for (var k in obj.dumps) {
				// ud.innerHTML += obj.dumps[k] + '<a class="fhqbtn" href="files/dumps/' + obj.dumps[k] + '">' + download + '</a><a class="fhqbtn" href="files/dumps/' + obj.dumps[k] + '">' + download + '</a><br>';
				ud.innerHTML += obj.dumps[k] + ' <a class="fhqbtn" href="files/dumps/' + obj.dumps[k] + '">Download</a><div class="fhqbtn" onclick="removeDumpUsers(\'' + obj.dumps[k] + '\');">Remove</div><br>';
			}

			var lu = document.getElementById("listUsers");
			lu.innerHTML = '';

			var found = parseInt(obj.found, 10);
			document.getElementById("search_found").innerHTML = found;

			var onpage = parseInt(obj.onpage, 10);
			var page = parseInt(obj.page, 10);

			lu.innerHTML += '<div id="user_paging">' + fhq.ui.paginator(0,found, onpage, page) + '</div>';
			
			
			var tbl = new FHQTable();
			
			tbl.openrow();
			tbl.cell('Logo');
			tbl.cell('ID / E-mail <br> Nick');
			tbl.cell('Last IP <br> Country / City');
			tbl.cell('Last Sign in <br> Status / Role');
			tbl.closerow();
		
			for (var k in obj.data) {
				var userinfo = obj.data[k];


				tbl.openrow();
				if (userinfo.logo != null) 
					tbl.cell('<img height="100px" src="' + userinfo.logo + '"/>');
				else
					tbl.cell('');

				tbl.cell( '#' + userinfo.userid + ' / '
					+ userinfo.email + ' <br> '
					+ userinfo.nick + ' <br> '
					+ fhqgui.btn('Info', 'showUserInfo(' + userinfo.userid + ');')
				);
				tbl.cell(
					userinfo.last_ip + '<br>'
					+ userinfo.country + ' / ' + userinfo.city + '<br>'
				);

				// TODO: if not activated can allow edit email and send mail again
				tbl.cell(
					userinfo.dt_last_login + '<br>'
					+ userinfo.status + ' / ' + userinfo.role
				);
				tbl.closerow();
			}

			lu.innerHTML += tbl.render();
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

function prepareDumpUsers() {
	var params = {};
	// alert(createUrlFromObj(params));
	send_request_post(
		'api/users/export.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				updateUsers();
				return;
			} else {
				alert(obj.error.message);
			}
		}
	);
}

function removeDumpUsers(filename) {
	var params = {};
	params.filename = filename;
	// alert(createUrlFromObj(params));
	send_request_post(
		'api/users/export_remove.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				updateUsers();
				return;
			} else {
				alert(obj.error.message);
			}
		}
	);
}

function formCreateUser() {
	var pt = new FHQParamTable();
	pt.row('Uuid:', '<input type="text" id="newuser_uuid" value="' + guid() + '"/>');
	pt.row('Logo:', '<input type="text" id="newuser_logo" value="files/users/0.png"/>');
	pt.row('E-mail:', '<input type="text" id="newuser_email" value=""/>');
	pt.row('Role:', fhqgui.combobox('newuser_role', 'user', fhq.getUserRoles()));
	pt.row('Nick:', '<input type="text" id="newuser_nick" value=""/>');
	pt.row('Password:', '<input type="password" id="newuser_password" value=""/>');
	pt.row('Status:', fhqgui.combobox('newuser_status', 'activated', fhq.getUserStatuses()));
	pt.right('<div class="fhqbtn" onclick="createUser();">Create</div>');
	pt.right('<div id="newuser_errors"></div>');
	pt.skip();
	showModalDialog(pt.render());
}

function createPageUsers() {

	var pt = new FHQParamTable();
	pt.row('',fhqgui.btn('Create User', 'formCreateUser();'));
	pt.row('',fhqgui.btn('Prepare dump of users (export)', 'prepareDumpUsers();') + '<div id="usersdump"></div>');
	pt.row('',fhqgui.btn('Import Users', 'importUsers();'));
	pt.skip();
	pt.row('E-mail or Nick:', '<input type="text" id="user_search" value="" onkeydown="if (event.keyCode == 13) {resetUsersPage(); updateUsers();};"/>');
	pt.row('Role:', fhqgui.combobox('user_role', '', fhq.getUserRolesFilter()));
	pt.row('Status:', fhqgui.combobox('user_status', '', fhq.getUserStatusesFilter()));
	pt.row('On Page:', fhqgui.combobox('user_onpage', '15', fhq.getOnPage()));
	pt.row('', fhqgui.btn('Search', 'resetUsersPage(); updateUsers();'));
	pt.skip();
	pt.row('Found:', '<font id="search_found">0</font>');
	var cp = new FHQContentPage();
	cp.clear();
	cp.append(pt.render());
	cp.append('<input type="hidden" id="user_page" value="0"/>'
		+ '<div id="error_search"></div>'
		+ '<hr/>'
		+ '<div id="listUsers"></div>');
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
