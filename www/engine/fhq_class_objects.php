<?php
	$rootdir = dirname(__FILE__);
	include_once("$rootdir/../config/config.php");
		
	//---------------------------------------------------------------------	

	class fhq_object
	{
		protected $defobjs = array();
		function fhq_object() {
			$file = dirname(__FILE__)."/../config/defobjs.json";
			$json = file_get_contents($file);
			var_dump(json_decode($json, true));
			// $defobjs = 
		}
		
		function fundObj($name) {
			
			
		}
	}
	//---------------------------------------------------------------------
?>
