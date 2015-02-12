<?php

include_once dirname(__FILE__)."/../tex.php";

$bShow = false;
if (!isset($doc)) {
	$bShow = true;
	$doc = array();
}

$doc['users'] = array(
	'name' => 'Users',
	'description' => 'methods for work with users or for user',
	'methods' => array( 
		'change_password' => array(
			'name' => 'Change User Password',
			'description' => 'Methods for change user password',
			'uri' => 'api/users/change_password.php',
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
		),
	),
);

if ($bShow)
	print_doc($doc);
