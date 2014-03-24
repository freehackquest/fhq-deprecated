<?php 
	$curdir = dirname(__FILE__);
	include_once("$curdir/../config/config.php");
	//---------------------------------------------------------------------
	class fhq_database
	{
		function connect($config)
		{
			$db = mysql_connect( 
				$config['db']['host'], 
				$config['db']['username'], 
				$config['db']['userpass']
			) 
			or die( 'could not connecting to mysql: "'
					.$config['db']['host'].'@'.$config['db']['username'].'"'
			);

			mysql_select_db( $config['db']['dbname'], $db) 
			or die('could not select database: "'.$config['db']['dbname'].'"');

			mysql_set_charset("utf8");
			return;
		}

		function query( $query )
		{
			/*if(!$this->db)
				die("don't connect to database");*/

			$result = mysql_query( $query );
			return $result;
		}

		function count( &$mysql_result )
		{
			$count = mysql_num_rows( $mysql_result );
			return $count;
		}

		var $db;
	};
	
	//---------------------------------------------------------------------
	
	function strtodb( $str )
	{
		return mysql_real_escape_string(htmlspecialchars($str));
	};
?>
