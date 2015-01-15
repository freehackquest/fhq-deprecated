function FHQFrontEndLib() {

	// helpers function
	this.createUrlFromObj = function(obj) {
		var str = "";
		for (k in obj) {
			if (str.length > 0)
				str += "&";
			str += encodeURIComponent(k) + "=" + encodeURIComponent(obj[k]);
		}
		return str;
	}
	this.baseUrl = "http://fhq.keva.su/";
	this.token = "";
	this.client = "FHQFrontEndLib.js";

	// post request to server Async
	this.sendPostRequest_Async = function(page, params, callbackf) {
		var tmpXMLhttp = null;
		params.token = this.token;
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			tmpXMLhttp = new window.XMLHttpRequest();
		};
		tmpXMLhttp.onreadystatechange=function() {
			if (tmpXMLhttp.readyState==4 && tmpXMLhttp.status==200) {
				if(tmpXMLhttp.responseText == "")
					obj = { "result" : "fail" };
				else
				{
					alert(tmpXMLhttp.responseText);
					var obj = JSON.parse(tmpXMLhttp.responseText);
					callbackf(obj);
					delete obj;
					delete tmpXMLhttp;
				}
			}
		}
		tmpXMLhttp.open("POST", this.baseUrl + page, true);
		tmpXMLhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		tmpXMLhttp.send(this.createUrlFromObj(params));
	};

	// post request to server Sync
	this.sendPostRequest_Sync = function(page, params) {
		params.token = this.token;
		// alert(this.createUrlFromObj(params));
		
		var tmpXMLhttp = null;
		
		var obj = null;
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			tmpXMLhttp = new window.XMLHttpRequest();
		};
		tmpXMLhttp.onreadystatechange=function() {
			if (tmpXMLhttp.readyState==4 && tmpXMLhttp.status==200) {
				if(tmpXMLhttp.responseText == "")
					obj = { "result" : "fail" };
				else
				{
					obj = JSON.parse(tmpXMLhttp.responseText);
					delete tmpXMLhttp;
				}
			}
		}
		tmpXMLhttp.open("POST", this.baseUrl + page, false);
		tmpXMLhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		tmpXMLhttp.send(this.createUrlFromObj(params));

		return obj;
	};

	this.auth = new (function(t) {
		this.p = t;
		this.sign_in = function (email, password) {
			var params = {};
			params.email = email;
			params.password = password;
			params.client = this.p.client;
			var obj = this.p.sendPostRequest_Sync('api/auth/sign_in.php', params);
			var bRes = obj.result == "ok";
			if (bRes)
				this.p.token = obj.token;
			else
				this.p.token = "";
			return bRes;
		};
		this.sign_out = function () {
			var params = {};
			var obj = this.p.sendPostRequest_Sync('api/auth/sign_out.php', params);
			this.p.token = "";
			return true;
		};
	})(this);

	
	this.games = new (function(t) {
		this.p = t;
		this.list = function() {
			var params = {};
			var obj = this.p.sendPostRequest_Sync('api/games/list.php', params);
			var bRes = obj.result == "ok";
			return bRes ? obj.data : obj.error;
		};
		this.get = function(gameid) {
			var params = {};
			params.id = gameid;
			var obj = this.p.sendPostRequest_Sync('api/games/get.php', params);
			var bRes = obj.result == "ok";
			return bRes ? obj.data : obj.error;
		};
		this.choose = function(gameid) {
			var params = {};
			params.id = gameid;
			var obj = this.p.sendPostRequest_Sync('api/games/choose.php', params);
			var bRes = obj.result == "ok";
			return bRes ? obj.data : obj.error;
		};
	})(this);
};
		

			
			
			
			/*function choose_game() {
				var params = {};
				params.id = 7;
				params.token = token;

				document.getElementById('error').innerHTML = "";
				send_request_post(
					base_url + 'api/games/choose.php',
					createUrlFromObj(params),
					function (obj) {
						if (obj.result == "ok") {
							document.getElementById('choose_game_result').innerHTML = "Choosed";
						} else {
							document.getElementById('error').innerHTML = obj.error.message;
							document.getElementById('choose_game_result').innerHTML = "Fail";
						}
					}
				);
			}*/
