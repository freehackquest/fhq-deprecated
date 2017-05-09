window.tools_replace_in_text = new function() {
	this.prefix = 'tools_replace_in_text';

	// init 
	this.init = function(e){
		var pfx = this.prefix;
		e.html('<center><h1>Replace in text</h1><br>'
			+ 'Please enter text:<br>'
			+ '<textarea id="' + pfx + '_input_text">Кимерtтекста.t"tR7aopletye7y</textarea><br><br>'
			+ '<p>Replace Map:</p> <div id="' + pfx + '_map"></div><br>'
			+ 'Result:<br>'
			+ '<textarea readonly=true id="' + pfx + '_output_text"></textarea><br><br><br>'
			+ '</center>');
			
		var inputtext = $('#' + pfx + '_input_text');
		var outputtext = $('#' + pfx + '_output_text');
		var replacemap = $('#' + pfx + '_map');
		
		inputtext.unbind().bind('change keyup paste', function(){
			console.log("need make map");
			// TODO get old map
			var old_map = tools_replace_in_text.getUserMap(replacemap);
			console.log(old_map);
			var new_map = tools_replace_in_text.makeReplaceMap(inputtext.val());
			replacemap.empty();
			var i = 0;
			for(var t in new_map){
				var k = t;
				var v = $("<div>").text(new_map[t]).html();
				if(old_map[k]){
					v = $("<div>").text(old_map[k]).html();
				}
				if(v == '"') v = "&quot;";
				var escaped_k = k;
				if(escaped_k == '"') escaped_k = "&quot;";
				
				replacemap.append(escaped_k + ' => <input type="text" char="' + escaped_k + '" value="' + v + '" size=1 />  ');
				i++;
				if(i % 6 == 0){
					replacemap.append('</p><p>');
				}
			}
			$('#' + pfx + '_map input').unbind().bind('change paste keyup', function(){
				var map = tools_replace_in_text.getUserMap(replacemap);
				var text = inputtext.val();
				var text2 = "";
				for(var i = 0; i < text.length; i++){
					var c = text[i];
					if(map[c]){
						text2 += map[c];
						// console.log(c + " => " + map[c]);
					}else{
						text2 += c;
						console.log("Not found " + c + " in map")
					}
				}
				console.log(text2);
				outputtext.val(text2);
			})
			
		});
		inputtext.change();
	}
	
	this.getUserMap = function(replacemap){
		var map = {};
		var inputs = replacemap.find('input');
		for(var i = 0; i < inputs.length; i++){
			var k = $(inputs[i]).attr('char');
			var v = $(inputs[i]).val();
			map[k] = v;
		}
		return map;
	}
	
	this.makeReplaceMap = function(text){
		var map = {};
		for(var i = 0; i < text.length; i++){
			map[text[i]] = text[i];
		}
		return map;
	}
	
	// dispose
	this.dispose = function(){
		
	}
};
