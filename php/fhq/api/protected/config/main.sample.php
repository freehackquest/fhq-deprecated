<?php

return array(
	'name'=>'CTF API',

	'defaultController'=>'method',
	
	// autoloading model and component classes
	'import'=>array(
		'application.components.*',
		'application.models.*',
	),

	'preload' => array('tokenValidate'),
	'components'=>array(
		'db'=>array(
			'class'=>'system.db.CDbConnection',
			'connectionString' => 'mysql:host=;dbname=',
			'emulatePrepare' => true,
			'username' => '',
			'password' => '',
			'charset' => 'utf8',
			'tablePrefix' => '',
		),

		'tokenValidate'=>array(
			'class' => 'TokenValidate',
		),

		'urlManager'=>array(
			'showScriptName'=>false, // Hide index.php
			'urlFormat'=>'path',

			'rules'=>array(
				array('token/auth', 'pattern' => 'token', 'verb'=>'GET'),
				array('<controller>/<action>', 'pattern' => 'method/<controller:\w+>.<action:\w+>', 'verb'=>'GET'),
				array('<controller>/<action>', 'pattern' => 'method/<controller:\w+>.<action:\w+>', 'verb'=>'POST'),
			),
		),
	),

	'params'=>array(
		'format'=>'json', //Format response data
		'version'=>'0.0.1 alpha',
		'tokenExpiresIn' => 60*60*24*15,
		'log_in' => false,
		'registration_allow' => true, //Позволить пользователям регистрироваться
		'send_mail_allow' => true, // Позволить серверу отправлять письма
		'paginator' => array(
			'count' => 50, // Количество записей, выдаваемых API по умолчанию
			'limit' => 500, // Максимальное количество записей
		),
	),
);