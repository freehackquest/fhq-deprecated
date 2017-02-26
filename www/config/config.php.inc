<?php

	// configure for connection to database
	$config = array();
	$config['db'] = array();
	$config['db']['host'] = "localhost";
	$config['db']['username'] = "freehackquest_u";
	$config['db']['userpass'] = "freehackquest_password_database";
	$config['db']['dbname'] = "freehackquest";

	// configure mail
	$config['mail'] = array();
	$config['mail']['from'] = "test@gmail.com";
	$config['mail']['host'] = "ssl://smtp.gmail.com";
	$config['mail']['port'] = "465";
	$config['mail']['auth'] = true;
	$config['mail']['username'] = "test@gmail.com";
	$config['mail']['password'] = "test";
	$config['mail']['allow'] = 'yes';
	$config['mail']['system_message_admin_email'] = 'yourmailforfeedbacks@domain.com';

	// configure registration
	$config['registration'] = array();
	$config['registration']['allow'] = 'yes';
	$config['registration']['type'] = 'simple';
	
	// configure profile
	$config['profile']['change_nick'] = 'yes';
	
	// playing with us show if value more then...
	$config['public_info']['cities_more_than'] = 10;
	$config['public_info']['cities_limit'] = 25;
	$config['public_info']['maximal_winners'] = 5;

	// google auth credentials
	$config['google_auth'] = array();
	$config['google_auth']['appname'] = "FreeHackQuest";
	$config['google_auth']['client_id'] = "*.googleusercontent.com";
	$config['google_auth']['client_secret'] = "*";
	$config['google_auth']['developer_key'] = "*";
	$config['google_auth']['oauth2google_uri'] = "http://freehackquest.com/oauth2google.php";
	$config['google_auth']['google_auth_uri'] = "http://freehackquest.com/google_auth.php";
