<?php

include_once dirname(__FILE__)."/../tex.php";

$bShow = false;
if (!isset($doc)) {
	$bShow = true;
	$doc = array();
}

if ($bShow)
	print_doc($doc);
