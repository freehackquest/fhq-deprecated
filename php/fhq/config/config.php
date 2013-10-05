<?

	// configure for connection to database
	$config = array();
	$config['db'] = array();
	$config['db']['host'] = "localhost";
	$config['db']['username'] = "freehackquest_u";
	$config['db']['userpass'] = "freehackquest_u";
	$config['db']['dbname'] = "freehackquest";
	$config['httpname'] = "http://localhost/fhq/";

	$config['secrets'][0] = "sol1";
	$config['secrets'][1] = "...";
	$config['secrets'][2] = "+++";
	$config['secrets'][3] = "---";
	$config['secrets'][4] = "===";

	$config['nfs_share'] = '/var/www/test';

	$config['targetDate'] = array();
	$config['targetDate']['day'] = 7;
	$config['targetDate']['month'] = 10;
	$config['targetDate']['year'] = 2013;
	$config['targetDate']['hour'] = 10;
	$config['targetDate']['minute'] = 0;
	$config['targetDate']['second'] = 0;

	$config['mail'] = array();
	$config['mail']['from'] = "test@gmail.com";
	$config['mail']['host'] = "ssl://smtp.gmail.com";
	$config['mail']['port'] = "465";
	$config['mail']['auth'] = true;
	$config['mail']['username'] = "test@gmail.com";
	$config['mail']['password'] = "test";
?>
