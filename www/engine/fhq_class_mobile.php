<?php
	class fhq_mobile()
	{
		function isMobile()
		{
			$browser = $_SERVER['HTTP_USER_AGENT']."\n\n";
			$pos = strpos($browser,"Mobile");
			return !($pos === false);
		}
	};
?>
