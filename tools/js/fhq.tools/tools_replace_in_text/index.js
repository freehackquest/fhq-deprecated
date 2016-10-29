window.tools_replace_in_text = new function() {
	this.prefix = 'tools_replace_in_text';

	// init 
	this.init = function(e){
		var pfx = this.prefix;
		e.html('<center><h1>Replace in text</h1><br>'
			+ 'Please enter text:<br>'
			+ '<textarea id="' + pfx + '_input_text">Кимерtтекста.t"tR7aopletye7y</textarea><br><br>'
			+ 'Replace rules: <input type="text" id="' + pfx + '_rules" value="R:E,o:m,7:x,t: ,К:Пр,y:t"><br>'
			+ '<i>Note: replacement performed in order to describe the rules</i><br><br>'
			+ '<div class="fhqbtn" id="' + pfx + '_btngo">Go</div><br><br>'
			+ 'Result:<br>'
			+ '<textarea readonly=true id="' + pfx + '_output_text"></textarea><br><br><br>'
			+ '</center>');

		$('#' + pfx + '_btngo').unbind('click').bind('click', function(){
			var text = $('#' + pfx + '_input_text').val();
			var rules = $('#' + pfx + '_rules').val().split(",");
			var output = window.tools_replace_in_text.replaceInText(text, rules);
			$('#' + pfx + '_output_text').val(output);
		});
	}
	
	this.replaceInText = function(text, rules){
		var replacerules = {};
		for(var i = 0; i < rules.length; i++)
		{
			var arr = rules[i].split(":");
			replacerules[arr[0]] = arr[1];
		}
		for(key in replacerules) {
			text = text.replace(new RegExp(key,'g'),replacerules[key]);
		}
		return text;
	}
	
	// dispose
	this.dispose = function(){
		
	}
};
