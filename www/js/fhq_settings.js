
function loadSettings() {
	var cp = document.getElementById('content_page');
	cp.innerHTML = '';
	send_request_post(
		'api/settings/get.php',
		'',
		function (obj) {
			if (obj.result == "fail") {
				cp.innerHTML = obj.error.message;
				return;
			}

			var content = '<div class="user_info_table">';
			for (var k in obj.data) {
				for (var k1 in obj.data[k]) {
					content += createUserInfoRow(k + '.' + k1, obj.data[k][k1]);
				}
				content += createUserInfoRow_Skip();
			}
			content += createUserInfoRow_Skip();
			content += '</div>';
			cp.innerHTML = content;
		}
	);
}
