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
	
	this.combobox = function(idelem, defaultvalue, arr) {
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
	
	this.readonly = function(idelem, value) {
		return '<div id="' + idelem +'">' + value + '</div>';
	};

	this.btn = function(caption, js) {
		return '<div class="fhqbtn" onclick="' + js + '">' + caption + '</div>';
	}
	
	this.textedit = function(idelem, text) {
		return '<textarea id="' + idelem + '">' + text + '</textarea>';
	}

	this.showModalDialog = function(content) {
		// document.getElementById('modal_dialog').style.top = document.body.
		document.getElementById('modal_dialog').style.visibility = 'visible';
		document.getElementById('modal_dialog_content').innerHTML = content;
		document.documentElement.style.overflow = 'hidden';  // firefox, chrome
		document.body.scroll = "no"; // ie only
		document.onkeydown = function(evt) {
			if (evt.keyCode == 27)
				closeModalDialog();
		}	
	}

	this.closeModalDialog = function() {
		document.getElementById('modal_dialog').style.visibility = 'hidden';
		document.documentElement.style.overflow = 'auto';  // firefox, chrome
		document.body.scroll = "yes"; // ie only
		document.onkeydown = null;
		document.getElementById('modal_dialog_content').innerHTML = "";
	}
};

function FHQParamTable() {
	this.paramtable = [];
	this.row = function(name,param) {
		this.paramtable.push( '\n'
			+ '\t<div class="fhqparamtbl_row">\n'
			+ '\t\t<div class="fhqparamtbl_param">' + name + '</div>\n'
			+ '\t\t<div class="fhqparamtbl_value">' + param + '</div>\n'
			+ '\t</div>\n'
		);
	};
	this.right = function(param) {
		this.paramtable.push( '\n'
			+ '\t<div class="fhqparamtbl_row">\n'
			+ '\t\t<div class="fhqparamtbl_param"></div>\n'
			+ '\t\t<div class="fhqparamtbl_value">' + param + '</div>\n'
			+ '\t</div>\n'
		);
	};
	this.left = function(name) {
		this.paramtable.push( '\n'
			+ '\t<div class="fhqparamtbl_row">\n'
			+ '\t\t<div class="fhqparamtbl_param">' + name + '</div>\n'
			+ '\t\t<div class="fhqparamtbl_value"></div>\n'
			+ '\t</div>\n'
		);
	};

	this.skip = function() {
		this.paramtable.push(
			'<div class="fhqparamtbl_rowskip"></div>'
		);
	};
	this.render = function() {
		var result = '\n';
		result += '<div class="fhqinfo">\n';
		result += '<div class="fhqparamtbl">\n';
		result += this.paramtable.join('\n');
		result += '</div>\n';
		result += '</div>\n';
		return result;
	};
}

// work with content page
function FHQContentPage() {
	this.cp = document.getElementById('content_page');
	if (this.cp)
		this.cp.innerHTML = 'Loading...';
	else
		throw 'Not found content_page';

	this.append = function(str) {
		this.cp.innerHTML += str;
	};
	this.clear = function() {
		this.cp.innerHTML = '';
	};
}

function FHQFeedback() {
	this.type = '';
	this.text = '';
	this.id = null;
	this.show = function(obj) {
		this.id = obj ? obj.id : null;
		this.type = obj ? obj.type : '';
		this.text = obj ? obj.text : '';

		var pt = new FHQParamTable();
		if (this.id != null)
			pt.row('ID:', fhqgui.readonly('editfeedback_id', this.id));
		pt.row('Type:', fhqgui.combobox('editfeedback_type', this.type, fhq.getFeedbackTypes()));
		pt.row('Message:', fhqgui.textedit('editfeedback_text', this.text));
		if (this.id == null)
			pt.right(fhqgui.btn('Create', 'insertFeedback();'));
		else
			pt.right(fhqgui.btn('Save', 'saveFeedback();'));
		fhqgui.showModalDialog(pt.render());
	};
	
	this.params = function() {
		var params = {};
		if (document.getElementById("editfeedback_id"))
			params.id = document.getElementById("editfeedback_id").innerHTML;
		params.text = document.getElementById("editfeedback_text").value;
		params.type = document.getElementById("editfeedback_type").value;
		return params;
	};

	this.close = function() {
		fhqgui.closeModalDialog();
	};
	
	this.save = function() {
		alert('feedback save nothing');
	};
	
	this.create = function() {
		alert('feedback create nothing');
	};
	
	this.delete = function() {
		alert('feedback create nothing');
	};
};

