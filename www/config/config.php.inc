<?php

	// configure for connection to database
	$config = array();
	$config['db'] = array();
	$config['db']['host'] = "localhost";
	$config['db']['username'] = "freehackquest_u";
	$config['db']['userpass'] = "freehackquest_u";
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
	
	// configure registration
	$config['registration'] = array();
	$config['registration']['allow'] = 'yes';
	$config['registration']['type'] = 'simple';
	
	// configure profile
	$config['profile']['change_nick'] = 'yes';
	
	// playing with us show if value more then..
	$config['public_info']['cities_more_than'] = 10;
	$config['public_info']['cities_limit'] = 25;
?>
