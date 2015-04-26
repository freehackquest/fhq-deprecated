
function installUpdates() {
	fhqgui.setFilter('updates');
	document.getElementById("content_page").innerHTML = "Install updates. Please wait...";
	var params = {};
	send_request_post(
		'api/updates/install_updates.php',
		createUrlFromObj(params),
		function (obj) {
			if (obj.result == "ok") {
				document.getElementById("content_page").innerHTML = "Current version: " + obj.version + "<br>";
				for (var k in obj.data) {
					document.getElementById("content_page").innerHTML += "Update [" + k + "] " + obj.data[k] + ".<br>";
				}
			} else {
				document.getElementById("content_page").innerHTML = obj.error.message + '<br>' + JSON.stringify(obj);
			}
		}
	);
}
