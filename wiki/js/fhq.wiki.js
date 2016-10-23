var menu = [
	{
		'name' : 'Информатика',
		'submenu' : [
			{
				'name' : 'Введение и кратко об операционных системах',
				'link' : 'about_operation_system.html'
			}, {
				'name' : 'Хранение и кодирование информации',
				'link' : 'index.html'
			}, {
				'name' : 'Векторное изображение',
				'link' : 'index.html'
			}, {
				'name' : 'Растровое изображение',
				'link' : 'index.html'
			}, {
				'name' : 'Звуковая информация',
				'link' : 'index.html'
			}, {
				'name' : 'base64 и hex',
				'link' : 'index.html'
			}, {
				'name' : 'Бинарные операции, конвертирование из/в 2,10,16чные системы счисления',
				'link' : 'index.html'
			}, {
				'name' : 'Кратко о популярных хэш-функциях',
				'link' : 'index.html'
			}, {
				'name' : 'Кратко о популярных криптографических алгоритмах',
				'link' : 'index.html'
			}, {
				'name' : 'Виртуализация',
				'submenu' : [ {
						'name' : 'Гипервизор',
						'link' : 'index.html'
					}, {
						'name' : 'Для чего нужна виртуализация?',
						'link' : 'index.html'
					}, {
						'name' : 'Виды виртуализации',
						'link' : 'index.html'
					}
				]
			}
		]
	}, {
		'name' : 'Aрхитектура колец защиты операционной системы',
		'submenu' : [ {
			'name' : 'Режим супервизора',
			'link' : 'index.html'
		}, {
			'name' : 'Режим гипервизора',
			'link' : 'index.html'
		} ]
	}, {
		'name' : 'Сети',
		'submenu' : [
			{
				'name' : 'OSI и основы',
				'link' : 'index.html'
			}, {
				'name' : 'Коммутация и ЛВС',
				'link' : 'index.html'
			}, {
				'name' : 'Маршрутизация и глобальные сети',
				'link' : 'index.html'
			}, {
				'name' : 'Беспроводные технологии',
				'link' : 'index.html'
			}, {
				'name' : 'Основные сервисы ( протоколы: http(s), ssh, ftp, samba; пакеты: ICMP Echo-Request, DHCP, DNS)',
				'link' : 'index.html',
				'submenu' : [
					{
						'name': 'Что такое DHCP?',
						'link' : 'index.html'
					} , {
						'name': 'Как работает DHCP?',
						'link' : 'index.html'
					}
				]
			}, {
				'name' : 'Утилиты (for linux: nmap, ping, ifconfig, dhcpclient, ssh, wget)',
				'link' : 'index.html',
				'submenu': [
					{
						'name': 'Сканирование (nmap)',
						'link' : 'index.html'
					}
				]
			}
		]
	}, {
		'name' : 'Разработка программных средств (программирование)',
		'submenu' : [
			{
				'name' : 'Инструментарий разработчика',
				'submenu': [
					{
						'name' : 'Система контроля версий git',
						'link' : 'programming_tools_git.html'
					}
				]
			} , {
				'name': 'Основы программирования функции и объекты-классы (или ООП)',
				'link' : 'programming_oop.html'
			} , {
				'name' : 'Assembler',
				'link': 'programming_assambler.html',
				'submenu' : [
					{
						'name': 'Кратко об устройстве процессора',
						'link' : 'programming_assambler_proc.html'
					}, {
						'name' : 'Списки комманд и примеры',
						'link' : 'programming_assambler_commands.html'
					}, {
						'name' : 'Заключение по ассемблеру',
						'link' : 'programming_assambler_ps.html'
					}
				]
			},{
				'name' : 'C++',
				'link' : 'programming_cpp.html'
			},{
				'name' : 'Python',
				'link' : 'programming_python.html'
			},{
				'name' : 'JavaScript',
				'link' : 'programming_javascript.html'
			}
		]
	}, {
		'name' : 'Unix (Основы и философия)',
		'submenu' : [
			{
				'name': 'Философия UNIX',
				'link' : 'unix_philosophi.html'
			}, {
				'name' : 'Правила Community',
				'link' : 'unix_community_rules.html'
			}, {
				'name' : 'Почему CLI лучше GUI?',
				'link' : 'unix_why_cli_best_then_gui.html'
			}, {
				'name' : 'Конвееры и потоки',
				'link' : 'unix_conveers_and_thread.html'
			}, {
				'name' : 'Фильтры и другие полезные утилиты',
				'link' : 'unix_filters_and_usefull_utilites.html'
			}
		]
	}, {
		'name' : 'Linux',
		'submenu' : [
			{
				'name': 'Кратко о Linux',
				'link' : 'linux_about.html'
			}, {
				'name' : 'Структура Linux (где что храниться)',
				'link' : 'linux_struct.html'
			}, {
				'name' : 'Просто полезные команды',
				'link' : 'linux_usefull_console_commands.html'
			}
		]
	}, {
		'name' : 'Команды для работы с VirtualBox из консоли',
		'link' : 'virtualbox.html'
	}, {
		'name' : 'Shell-программирование',
		'link': 'shell_programming.html',
		'submenu' : [
			{
				'name': 'Основы',
				'link': 'shell_programming_bases.html'
			}
		]
	}, {
		'name' : 'Базы данных',
		'link': 'databases.html',
		'submenu' : [
			{
				'name': 'Определения',
				'link': 'databases_definitions.html'
			}
		]
	}, {
		'name' : 'Правовая часть',
		'link' : 'legal_part.html'
	}, {
		'name' : 'Что можно почитать дополнительно',
		'submenu' : [
			{
				'name': 'Криптография',
				'link': 'forread_crypto.html'
			}, {
				'name': 'Сети',
				'link': 'forread_network.html'
			}, {
				'name': 'Программирование',
				'link': 'forread_programming.html'
			}, {
				'name': 'Администрирование Unix, Linux, BSD',
				'link': 'forread_admin_linux_unix_bsd.html'
			}, {
				'name': 'Взлом',
				'link': 'forread_hacking.html'
			}
		]
	}, {
		'name' : 'Анонимность в сети Интернет',
		'link': 'anonimus.html',
		'submenu' : [
			{
				'name': 'Прокси-сервер',
				'link': 'anonimus_proxy.html'
			}, {
				'name': 'VPN подключение',
				'link': 'anonimus_vpn.html'
			}, {
				'name': 'Выделенный сервер',
				'link' : 'anonimus_vps.html'
			}, {
				'name': 'TOR',
				'link' : 'anonimus_tor.html'
			}, {
				'name': 'I2P',
				'link' : 'anonimus_i2p.html'
			}
		]
	}, {
		'name' : 'Список источников',
		'link' : 'list_of_sources.html'
	}
];

$(document).ready(function(){
	
	var content_menu = "Содержание";
	var numbers = [];
	
	function process_submenu(submenu){
		numbers.push(0);
		content_menu += "<ul>"
		for(var i in submenu){
			var o = submenu[i];
			numbers[numbers.length-1] = numbers[numbers.length-1] + 1;
			var num = numbers.join('.');
			if(o.name && o.link ){
				content_menu += '<li><a href="' + o.link + '">' + num + " " + o.name + '</a></li>';
			}else if(o.name){
				content_menu += '<li>' + num + " " + o.name + '</li>';
			}
			if(o.submenu){
				process_submenu(o.submenu)
			}
		}
		content_menu += '</ul>';
		numbers.pop();
	}
	process_submenu(menu);
	
	$('.menu').html(content_menu);
})
