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
						'link' : 'inde.html'
					}
				]
			} , {
				'name': 'Основы программирования функции и объекты-классы (или ООП)',
				'link' : 'index.html'
			} , {
				'name' : 'Assembler',
				'submenu' : [
					{
						'name': 'Кратко об устройстве процессора',
						'link' : 'index.html'
					}, {
						'name' : 'Списки комманд и примеры',
						'link' : 'index.html'
					}, {
						'name' : 'Заключение по ассемблеру',
						'link' : 'index.html'
					}
				]
			},{
				'name' : 'C++',
				'link' : 'index.html'
			},{
				'name' : 'Python',
				'link' : 'index.html'
			},{
				'name' : 'JavaScript',
				'link' : 'index.html'
			}
		]
	}, {
		'name' : 'Unix (Основы и философия)',
		'submenu' : [
			{
				'name': 'Философия UNIX',
				'link' : 'index.html'
			}, {
				'name' : 'Правила Community',
				'link' : 'index.html'
			}, {
				'name' : 'Почему CLI лучше GUI?',
				'link' : 'index.html'
			}, {
				'name' : 'Конвееры и потоки',
				'link' : 'index.html'
			}, {
				'name' : 'Фильтры и другие полезные утилиты',
				'link' : 'index.html'
			}
		]
	}, {
		'name' : 'Linux',
		'submenu' : [
			{
				'name': 'Кратко о Linux',
				'link' : 'index.html'
			}, {
				'name' : 'Структура Linux (где что храниться)',
				'link' : 'index.html'
			}, {
				'name' : 'Просто полезные команды',
				'link' : 'index.html'
			}
		]
	}, {
		'name' : 'Команды для работы с VirtualBox из консоли',
		'submenu' : [
		]
	}, {
		'name' : 'Shell-программирование',
		'submenu' : [
			{
				'name': 'Основы',
				'link': 'index.html'
			}
		]
	}, {
		'name' : 'Базы данных',
		'submenu' : [
			{
				'name': 'Определения',
				'link': 'index.html'
			}
		]
	}, {
		'name' : 'Правовая часть',
		'link' : 'index.html'
	}, {
		'name' : 'Что можно почитать дополнительно',
		'submenu' : [
			{
				'name': 'Криптография',
				'link': 'index.html'
			}, {
				'name': 'Сети',
				'link': 'index.html'
			}, {
				'name': 'Программирование',
				'link': 'index.html'
			}, {
				'name': 'Администрирование Unix, Linux, BSD',
				'link': 'index.html'
			}, {
				'name': 'Взлом',
				'link': 'index.html'
			}
		]
	}, {
		'name' : 'Анонимность в сети Интернет (Как прятаться и не спалиться)',
		'submenu' : [
			{
				'name': 'Прокси-сервер',
				'link': 'index.html'
			}, {
				'name': 'VPN подключение',
				'link': 'index.html'
			}, {
				'name': 'Выделенный сервер',
				'link' : 'index.html'
			}, {
				'name': 'TOR',
				'link' : 'index.html'
			}, {
				'name': 'I2P',
				'link' : 'index.html'
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
