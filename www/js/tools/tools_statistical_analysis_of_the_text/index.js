window.tools_statistical_analysis_of_the_text = new function() {
	this.prefix = 'tools_statistical_analysis_of_the_text';
	
	// init
	this.init = function(e){
		var pfx = this.prefix;
		e.html('<center><h1>Statistical analysis of the text</h1><br>'
			+ 'Please enter text:<br>'
			+ '<textarea id="' + pfx + '_input_text">Пример текста. " Example text</textarea><br><br>'
			+ '<div class="fhqbtn" id="' + pfx + '_btngo">Go</div><br><br>'
			+ 'Result:<br>'
			+ '<textarea readonly=true style="height: 250px" id="' + pfx + '_output_text"></textarea><br><br><br>'
			+ '</center>');

		$('#' + pfx + '_btngo').unbind('click').bind('click', function(){
			var input_text = $('#' + pfx + '_input_text').val();
			input_text = window.tools_statistical_analysis_of_the_text.statAnalizText(input_text);
			$('#' + pfx + '_output_text').val(input_text);
		});
	}
	
	this.statAnalizText = function(text){
		var stat = {};
		var result = '';
		var count = 0;
		for(var i = 0; i < text.length; i++) {
			var ch = text[i];
			count++;
			if (!stat[ch]) {
				stat[ch] = 1;
			} else {
				stat[ch]++;
			}
		}
		for(key in stat) {
			var val = stat[key];
			result += '[' + key + '] = ' + val + '/' + count + ' ( ' + Math.floor((val*100)/count) + ' %)\n';
		}
		return result;
	}
	
	// dispose
	this.dispose = function(){
		
	}
};
