var menu = [
	{
		'name' : 'Информатика',
		'submenu' : [
			{
				'name' : 'Введение и кратко об операционных системах',
				'link' : 'about_operation_system.html'
			}, {
				'name' : 'Хранение и кодирование информации',
				'link' : 'about_store_and_coding_information.html'
			}, {
				'name' : 'Векторное изображение',
				'link' : 'about_vector_image.html'
			}, {
				'name' : 'Растровое изображение',
				'link' : 'about_rast_image.html'
			}, {
				'name' : 'Звуковая информация',
				'link' : 'about_binary_sound.html'
			}, {
				'name' : 'base64 и hex',
				'link' : 'about_base64_and_hex.html'
			}, {
				'name' : 'Бинарные операции, конвертирование из/в 2,10,16чные системы счисления',
				'link' : 'about_binary_operations.html'
			}, {
				'name' : 'Хэш-функции',
				'link' : 'about_hash_functions.html'
			}, {
				'name' : 'Криптографические алгоритмы',
				'link' : 'about_crypto_algorithms.html'
			}, {
				'name' : 'Виртуализация',
				'link': 'virtualization.html',
				'submenu' : [ {
						'name' : 'Гипервизор',
						'link' : 'virtualization_hypervisor.html'
					}, {
						'name' : 'Для чего нужна виртуализация?',
						'link' : 'virtualization_why.html'
					}, {
						'name' : 'Виды виртуализации',
						'link' : 'virtualization_types.html'
					}
				]
			}
		]
	}, {
		'name' : 'Aрхитектура колец защиты операционной системы',
		'link': 'architech_defence_os.html',
		'submenu' : [ {
			'name' : 'Режим супервизора',
			'link' : 'architech_defence_os_supervisor.html'
		}, {
			'name' : 'Режим гипервизора',
			'link' : 'architech_defence_os_hypervisor.html'
		} ]
	}, {
		'name' : 'Сети',
		'link' : 'network.html',
		'submenu' : [
			{
				'name' : 'OSI и основы',
				'link' : 'network_osi.html'
			}, {
				'name' : 'Коммутация и ЛВС',
				'link' : 'network_commutation.html'
			}, {
				'name' : 'Маршрутизация и глобальные сети',
				'link' : 'network_routing.html'
			}, {
				'name' : 'Беспроводные технологии',
				'link' : 'network_wireless.html'
			}, {
				'name' : 'Основные сервисы',
				'link' : 'network_base_services.html',
				'submenu' : [
					{
						'name': 'Что такое DHCP?',
						'link' : 'network_dhcp_what.html'
					} , {
						'name': 'Как работает DHCP?',
						'link' : 'network_dhcp_how.html'
					}
				]
			}, {
				'name' : 'Сетевые утилиты',
				'link' : 'network_tools.html',
				'submenu': [
					{
						'name': 'Сканирование (nmap)',
						'link' : 'network_tools_nmap.html'
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
