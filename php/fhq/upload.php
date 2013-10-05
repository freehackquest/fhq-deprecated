<?php
//upload.php
$output_dir = "files/";
 
if(isset($_FILES['file']))
{
		echo "found FILES file\n";
}

 
if(isset($_POST['file']))
{
		echo "found POST file\n";
}

echo print_r($_FILES);
echo print_r($_FILES);
 
if(count($_FILES) > 0 && isset($_GET["prefix"]))
{
	$keys = array_keys($_FILES);
	$prefix = $_GET["prefix"];
	for($i = 0; $i < count($keys); $i++)
	{
		$filename = $keys[$i];
		if ($_FILES[$filename]['error'] > 0)
		{
			echo "Error: " . $_FILES[$filename]["error"] . "<br>";
		}
		else
		{
			$full_filename = $output_dir.$prefix.$filename;
			move_uploaded_file($_FILES[$filename]["tmp_name"],$full_filename);
			echo "Uploaded File: ".$full_filename."<br>";
			if(!file_exists($full_filename))
			  echo "ERROR!";
		}
	}
}
else
{
	echo "not found parametrs: upload_file";
}
exit;
?>
