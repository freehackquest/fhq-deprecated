if(!window.fhq) window.fhq = {};
if(!window.fhq.ui) window.fhq.ui = {};

fhq.ui.loadTools = function(){
	fhq.changeLocationState({'tools':''});
	fhq.ui.hideLoading();
	$('#content_page').html('<div class="toolinfo"></div><div class="toolslist"></div>');
	
	var len = fhq.tools.length;
	
	$('.toolslist').html('');
	$('.toolslist').append('<div class="tools"><div class="icon">' + fhq.t('Tools') + '</div><div class="content"></div></div>');
	
	for(var i = 0; i < len; i++){
		var tool = fhq.tools[i];
		if(tool.type == 'tools'){
			$('.toolslist .tools .content').append('<div class=toolitem toolid="' + tool.id + '"><div class="name">' + tool.name[fhq.lang()] + '</div></div>');	
		}
	}
	$('.toolitem').unbind('click').bind('click', function(){
		fhq.ui.loadTool($(this).attr('toolid'));
	});
}

fhq.ui.loadTool = function(toolid){
	fhq.ui.hideLoading();
	fhq.ui.loadTools();
	fhq.changeLocationState({'tool': toolid});
	$('.toolinfo').html(fhq.t('Loading...'));
	
	var tool = document.createElement('script');
	tool.src = "./js/tools/" + toolid + "/index.js";
	tool.onload = function() {
		$('.toolinfo').html('');
		window[toolid].init($('.toolinfo'));
	}
	tool.onerror = tool.onload;
	document.head.appendChild(tool);
}

// list of tools

fhq.tools = [
	{
		'type': 'tools',
		'id': 'tools_replace_in_text',
		'name': {
			'ru': 'Замена в тексте',
			'en': 'Replace in text'
		}
	},
	{
		'type': 'tools',
		'id': 'tools_statistical_analysis_of_the_text',
		'name': {
			'ru': 'Статистический анализ текста',
			'en': 'Statistical analysis of the text'
		}
	},
	{
		'type': 'tools',
		'id': 'tools_base64',
		'name': {
			'ru': 'Base64 Encode/Decode',
			'en': 'Base64 Encode/Decode'
		}
	}
];
