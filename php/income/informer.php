<?
	if(isset($config)){
		mysql_connect($config['database']['host'], $config['database']['username'], $config['database']['password']) or die($config['messages']['technical']);
		mysql_select_db($config['database']['database']) or die($config['messages']['technical']);

		$query = mysql_query('SELECT * FROM informers_income ORDER BY informer_id DESC LIMIT 1') or die(mysql_error());
		if(mysql_num_rows($query) > 0){
				$a = mysql_fetch_array($query);
				$config ['targetDate'] = date_parse($a['informer_date']);
				$informer_message = $a['informer_message'];
		}
	}

?>