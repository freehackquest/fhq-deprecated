<?php

include_once dirname(__FILE__)."/../tex.php";

$bShow = false;
if (!isset($doc)) {
	$bShow = true;
	$doc = array();
}

$doc['security'] = array(
	'name' => 'Security',
	'description' => 'Methods for login, logout, registration and restore password.',
	'methods' => array(
		'login' => array(
			'name' => 'Login',
			'description' => 'Methods for login user in the system',
			'uri' => 'api/security/login.php',
			'access' => 'all',
			'input' => array(
				'email' => array(
					'type' => 'string',
					'description' => 'Identificator of the user',
				),
				'password' => array(
					'type' => 'string',
					'description' => 'Password of a user',
				),
				'client' => array(
					'type' => 'string',
					'description' => 'Indentifier for frontend',
				),
			),
			'output' => array(
				'errors' => array(
					'1001' => 'Parameter email was not found',
					'1316' => 'Parameter password was not found',
					'1002' => 'email {%email%} and password was not found in system ',
				),
				'successfull' => array(
					'result' => 'ok',
					"token" => "76558894-0AA9-11E4-09F0-D353D3CF86D5",
				),
			),
		),
		'logout' => array(
			'name' => 'Logout',
			'description' => 'Methods for change user password',
			'uri' => 'api/security/logout.php',
			'access' => 'authorized users',
			'input' => array(
				'token' => array(
					'type' => 'string',
					'description' => 'access token for user',
				),
			),
			'output' => array(
				'errors' => array(
				),
				'successfull' => array(
					'result' => 'ok',
				),
			),
		),
		'registration' => array(
			'name' => 'Registration',
			'description' => 'Method for registration in the system.',
			'uri' => 'api/security/registration.php',
			'access' => 'all',
			'input' => array(
				'email' => array(
					'type' => 'string',
					'description' => 'user\'s email',
				),
				'captcha' => array(
					'type' => 'string',
					'description' => 'here -> api/captcha.php',
				),
				'client' => array(
					'type' => 'string',
					'description' => 'indentifier of frontend',
				),
			),
			'output' => array(
				'errors' => array(
					'1013' => 'Parameter email was not found',
					'1043' => 'Parameter captcha was not found',
					'1012' => '[Registration] Captcha is not correct, please "Refresh captcha" and try again',
					'1011' => '[Registration] Invalid e-mail address.',
					'1192' => '[Registration] This e-mail was already registered.',
					'1287' => '[Registration] Sorry registration is broken. Please send report to the admin about this.',
				),
				'successfull' => array(
					'result' => 'ok',
					'data' => array(),
				),
			),
		),
		'restore' => array(
			'name' => 'Restore Password',
			'description' => 'Methods for restore user password.',
			'uri' => 'api/security/restore.php',
			'access' => 'all',
			'input' => array(
				'email' => array(
					'type' => 'string',
					'description' => 'User\'s email',
				),
				'captcha' => array(
					'type' => 'string',
					'description' => 'here -> api/captcha.php',
				),
				'client' => array(
					'type' => 'string',
					'description' => 'indentifier of frontend',
				),
			),
			'output' => array(
				'errors' => array(
					'1038' => 'Parameter email was not found',
					'1039' => 'Parameter captcha was not found',
					'1040' => '[Restore] Captcha is not correct, please "Refresh captcha" and try again',
					'1041' => '[Restore] Invalid e-mail address.',
					'1042' => '[Restore] This e-mail was not registered.',
					'1315' => '[Restore] Sorry restore is broken. Please send report to the admin about this.',
				),
				'successfull' => array(
					'result' => 'ok',
					'data' => array(),
				),
			),
		),
    // end
	),
);

if ($bShow)
	print_doc($doc);
