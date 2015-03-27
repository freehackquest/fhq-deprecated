
function showSystemMenu() {
	var e = document.getElementById("system_menu");
	if (e.style.display == "none")
		e.style.display = "block";
	else
		e.style.display = "none";	
}

function logout() {
	var params = {};
	send_request_post(
		'api/auth/sign_out.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				window.location.href = 'index.php';
			} else {
				
			}
		}
	);
}
