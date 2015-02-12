<?php

include_once dirname(__FILE__)."/../tex.php";

$bShow = false;
if (!isset($doc)) {
	$bShow = true;
	$doc = array();
}

$doc['auth'] = array(
	'name' => 'Authorization',
	'description' => 'Methods for authorization, registration and restore password.',
	'methods' => array(

    // sign in
    'sign_in' => array(
			'name' => 'Sign In / Log in',
			'description' => 'Methods for login user in the system',
			'uri' => 'api/auth/sign_in.php',
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
    /*'sign_out' => array(
			'name' => 'Change User Password',
			'description' => 'Methods for change user password',
			'uri' => 'api/auth/change_password.php',
			'access' => 'authorized users',
			'input' => array(
				'old_password' => array(
					'type' => 'string',
					'description' => 'Old password',
				),
				'new_password' => array(
					'type' => 'string',
					'description' => 'New password (muts be more then 3 characters)',
				),
				'new_password_confirm' => array(
					'type' => 'string',
					'description' => 'New password confirm',
				),
			),
			'output' => array(
				'errors' => array(
					'7800' => 'Not found parameter "old_password"',
					'7801' => 'Not found parameter "new_password"',
					'7802' => 'Not found parameter "new_password_confirm"',
					'7803' => '"New password" must be more then 3 characters',
					'7804' => 'New password and New password confirm are not equals',
					'7805' => 'Old password are incorrect',
					'7806' => 'errors from db',
					'7807' => 'errors from db',
				),
				'successfull' => array(
					'result' => 'ok',
					'data' => array(),
				),
			),
		),*/
    // registration
    /* 'registration' => array(
			'name' => 'Change User Password',
			'description' => 'Methods for change user password',
			'uri' => 'api/auth/change_password.php',
			'access' => 'authorized users',
			'input' => array(
				'old_password' => array(
					'type' => 'string',
					'description' => 'Old password',
				),
				'new_password' => array(
					'type' => 'string',
					'description' => 'New password (muts be more then 3 characters)',
				),
				'new_password_confirm' => array(
					'type' => 'string',
					'description' => 'New password confirm',
				),
			),
			'output' => array(
				'errors' => array(
					'7800' => 'Not found parameter "old_password"',
					'7801' => 'Not found parameter "new_password"',
					'7802' => 'Not found parameter "new_password_confirm"',
					'7803' => '"New password" must be more then 3 characters',
					'7804' => 'New password and New password confirm are not equals',
					'7805' => 'Old password are incorrect',
					'7806' => 'errors from db',
					'7807' => 'errors from db',
				),
				'successfull' => array(
					'result' => 'ok',
					'data' => array(),
				),
			),
		), */
    // restore
    /*'restore' => array(
			'name' => 'Change User Password',
			'description' => 'Methods for change user password',
			'uri' => 'api/auth/change_password.php',
			'access' => 'authorized users',
			'input' => array(
				'old_password' => array(
					'type' => 'string',
					'description' => 'Old password',
				),
				'new_password' => array(
					'type' => 'string',
					'description' => 'New password (muts be more then 3 characters)',
				),
				'new_password_confirm' => array(
					'type' => 'string',
					'description' => 'New password confirm',
				),
			),
			'output' => array(
				'errors' => array(
					'7800' => 'Not found parameter "old_password"',
					'7801' => 'Not found parameter "new_password"',
					'7802' => 'Not found parameter "new_password_confirm"',
					'7803' => '"New password" must be more then 3 characters',
					'7804' => 'New password and New password confirm are not equals',
					'7805' => 'Old password are incorrect',
					'7806' => 'errors from db',
					'7807' => 'errors from db',
				),
				'successfull' => array(
					'result' => 'ok',
					'data' => array(),
				),
			),
		), */
    // end
	),
);

if ($bShow)
	print_doc($doc);
