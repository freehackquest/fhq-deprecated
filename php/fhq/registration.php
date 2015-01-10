<?php
include_once "engine/fhq.php";
// include dirname(__FILE__)."/config/config.php";

// ---------------------------------------------------------------------

if(isset($_GET['foractivate']))
{
	echo_shortpage(new fhq_page_foractivate());
	exit;
};

// ---------------------------------------------------------------------
echo_shortpage(new fhq_page_registration());

exit;
