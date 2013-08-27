

<?
	include "basepage.php";
	
	
	$title = "Free CTF";
	$scripts = "";
	$main = "";
	
	$main = $main.get_text_on_index();
	echo_page( $title, $scripts, $main );
?>