function FHQGuiLib() {
	this.createComboBox = function(idelem, defaultvalue, arr) {
		var result = '<select id="' + idelem + '">';
		for (var k in arr) {
			result += '<option ';
			if (arr[k].value == defaultvalue)
				result += ' selected ';
			result += ' value="' + arr[k].value + '">';
			result += arr[k].caption + '</option>';
		}
		result += '</select>';
		return result;
	};
	
	/*this. function createUserInfoRow(name, param) {
		return '<div class="user_info_row"><div class="user_info_param">' + name + '</div><div class="user_info_value">' + param + '</div></div>\n';
	}

	function createUserInfoRow_Skip() {
		return '<div class="user_info_row_skip"></div>\n';
	}*/
};
