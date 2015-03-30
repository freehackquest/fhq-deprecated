<?php

include_once dirname(__FILE__)."/../tex.php";

$bShow = false;
if (!isset($doc)) {
	$bShow = true;
	$doc = array();
}

$doc['auth'] = array(
	'name' => 'Security',
	'description' => 'Methods for authorization, registration and restore password.',
  'uri' => 'api/security/',
	'methods' => array(

    // sign in
    'sign_in' => array(
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
					'description' => 'Indentifier for client (fhq-web or other frontends)',
				),
			),
			'output' => array(
				'errors' => array(
					'1001' => 'Parameters was not found email or password',
					'1002' => 'Email or password was not found in system',
				),
				'successfull' => array(
					'result' => 'ok',
					"token" => "76558894-0AA9-11E4-09F0-D353D3CF86D5",
				),
			),
		),


    // sign_out
    'sign_out' => array(
			'name' => 'Sign Out/Logoff',
			'description' => 'Methods for change user password',
			'uri' => 'api/security/logout.php',
			'access' => 'authorized users',
			'input' => array(
        'token' => array(
					'type' => 'string',
					'description' => 'Access token for user',
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


    // registration
    'registration' => array(
			'name' => 'Change User Password',
			'description' => 'Methods for change user password',
			'uri' => 'api/security/registration.php',
			'access' => 'all',
			'input' => array(
  			'email' => array(
					'type' => 'string',
					'description' => 'User\'s email',
				),
				'captcha' => array(
					'type' => 'string',
					'description' => 'See section about captcha.',
				),
			),
			'output' => array(
				'errors' => array(
					'1010' => 'Problem with registration',
					'1011' => 'Invalid e-mail address',
					'1012' => 'Captcha is not correct, please "Refresh captcha"',
					'1013' => 'Incorrect input parameters email or captcha',
				),
				'successfull' => array(
					'result' => 'ok',
					'data' => array(),
				),
			),
		),


    // restore
    'restore' => array(
			'name' => 'Restore Password',
			'description' => 'Methods for restore user password',
			'uri' => 'api/security/restore.php',
			'access' => 'all',
			'input' => array(
				'email' => array(
					'type' => 'string',
					'description' => 'User\'s email',
				),
				'captcha' => array(
					'type' => 'string',
					'description' => 'See section about captcha.',
				),
			),
			'output' => array(
				'errors' => array(
					'1003' => 'Captcha is not correct, please "Refresh captcha" and try again',
					'1004' => 'Invalid e-mail address',
					'1005' => 'This e-mail was not registered',
					'1006' => 'Restore is denied',
					'1007' => 'Problem with sending email',
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
