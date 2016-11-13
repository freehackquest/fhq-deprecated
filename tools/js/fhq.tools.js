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
	
	var tool = document.createElement('script');
	tool.src = "./js/fhq.tools/" + toolid + "/index.js";
	tool.onload = function() {
		$('.toolinfo').html('');
		window[toolid].init($('.toolinfo'));
	}
	tool.onerror = tool.onload;
	document.head.appendChild(tool);
}

$(document).ready(function() {
	fhq.ui.loadTools();
	$('#btnmenu_archive').append(fhq.t('Archive'));
	$('#btnmenu_tools').append(fhq.t('Tools'));
	$('#btnmenu_classbook').append(fhq.t('Classbook'));
	
	function applyDark(){
		$("body").addClass('dark');
		$('#btnmenu_colorscheme img').attr({'src': 'http://freehackquest.com/images/menu/lightside_150x150.png'});
		$('#btnmenu_colorscheme_text').html(fhq.t('Light'));
		localStorage.setItem('colorscheme', 'dark');
	}
	
	function applyLight(){
		$("body").removeClass('dark');
		$('#btnmenu_colorscheme img').attr({'src': 'http://freehackquest.com/images/menu/darkside_150x150.png'});
		$('#btnmenu_colorscheme_text').html(fhq.t('Dark'));
		localStorage.setItem('colorscheme', 'light');
	}

	$('#btnmenu_colorscheme').unbind().bind('click', function(e){
		if($("body").hasClass("dark")){
			applyLight();
		}else{
			applyDark();
		}
	});
	
	if(localStorage.getItem('colorscheme') == 'dark'){
		applyDark();
	}else{
		applyLight();
	}
	
	if(fhq.containsPageParam('toolid')){
		fhq.ui.loadTool(fhq.pageParams['toolid']);
	}
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
