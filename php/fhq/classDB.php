<?php 
//---------------------------------------------------------------------
	class database
	{
		function connect()
		{
			include "config.php";
			/*
			echo "
db_host = $db_host <br>
db_username = $db_username <br>
			";
			echo ""
			*/

			$this->db = mysql_connect( $db_host, $db_username,
							$db_userpass); // or die("don't connect to db");
			if(!$this->db)
				die("don't connect to database");

			mysql_select_db( $db_namedb, $this->db) or die("don't exists db");
			return true;
		}

		function query( $query )
		{
			if(!$this->db)
				die("don't connect to database");

			$result = mysql_query( $query, $this->db );
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
