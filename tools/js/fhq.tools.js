if(!window.fhq) window.fhq = {};
if(!window.fhq.ui) window.fhq.ui = {};

window.fhq.ui.loadTools = function(){
	$('#content_page').html('<div class="toolinfo"></div><div class="toolslist"></div>');
	
	var len = fhq.tools.length;
	
	$('.toolslist').html('');
	$('.toolslist').append('<div class="tools"><div class="icon">Tools</div><div class="content"></div></div>');
	
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

window.fhq.ui.loadTool = function(toolid){
	fhq.changeLocationState({'tools' : '', 'toolid': toolid});
	$('.toolinfo').html('Loading...');
	$.getScript("./js/fhq.tools/" + toolid + "/index.js", function(){
		$('.toolinfo').html('');
		window[toolid].init($('.toolinfo'));
	});	
}

$(document).ready(function() {
	fhq.ui.loadTools();
	$('#btnmenu_archive').append(fhq.t('Archive'));
	$('#btnmenu_tools').append(fhq.t('Tools'));
	$('#btnmenu_classbook').append(fhq.t('Classbook'));
});

// list of tools

window.fhq.tools = [
	{
		'type': 'tools',
		'id': 'tools_hello_world',
		'name': {
			'ru': 'Привет мир!',
			'en': 'Hello world!'
		}
	},
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
