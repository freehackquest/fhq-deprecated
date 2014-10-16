<?
  /*
    sea-kg: if not working: try for debian "sudo apt-get install php5-gd" and than restart apache
  */
	function create_capcha_image($str, $font_name, $backgraund_jpg)
	{
		$img = imagecreatefromjpeg($backgraund_jpg); // 89?30 px
		$color = imagecolorallocate( $img, 0, 0, 0 );
		$str_arr = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
		$font_size = 20;
		$x_pos = 10;
		$y_pos = 40;
		for ( $i = 0; $i < strlen($str); $i++ )
		{
			$angle = mt_rand(-25, 25);
			imagettftext($img, $font_size, $angle, $x_pos, $y_pos, $color, $font_name, $str[$i]);
			$x_pos = $x_pos + 35;
		}
		return $img;
	};
	
	//generate random string
	function rc($count)
	{
		$chars="QWERTYUPASDFGHJKZXCVBNM";
		$str = "";
		for ( $i = 0; $i < $count; $i++ )
		{
			$str = $str.$chars[rand(0, (strlen($chars)-1))];
		};
		return $str;
	}

	$str = rc(4);
	session_start();
	$_SESSION['captcha_reg'] = $str;
	$captcha = create_capcha_image($str,"templates/base/fonts/Bleeding_Cowboys.ttf","templates/base/images/background_captcha.jpg");
	header('Expires: Sat, 31 May 2008 05:00:00 GMT'); 
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header('Cache-Control: no-store, no-cache, must-revalidate'); 
	header('Cache-Control: post-check=0, pre-check=0', FALSE); 
	header('Pragma: no-cache');  
	header("Content-Type: image/x-png");
	imagepng($captcha);
  imagedestroy($captcha);
?>
