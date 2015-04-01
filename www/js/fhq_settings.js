
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
			var pt = new FHQParamTable();
			for (var k in obj.data) {
				for (var k1 in obj.data[k]) {
					pt.row(k+'.'+k1, obj.data[k][k1]);
				}
				pt.skip();
			}
			pt.skip();
			cp.innerHTML = pt.render();
		}
	);
}
