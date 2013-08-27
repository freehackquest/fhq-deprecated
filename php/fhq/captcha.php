<?
	function create_capcha_image($str, $font_name, $backgraund_jpg) // аргумент Ц последовательность символов на капче
	{
		$img = imagecreatefromjpeg($backgraund_jpg); // 89?30 px
		$color = imagecolorallocate( $img, 0, 0, 0 );

		$str_arr = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY); // разбиваем строку на массив символов
		$font_size = 20;
		$x_pos = 10;
		$y_pos = 40;
		for ( $i = 0; $i < strlen($str); $i++ )
		{
			$angle = mt_rand(-25, 25); // поворачива€ еЄ на случайное количество градусов
			imagettftext($img, $font_size, $angle, $x_pos, $y_pos, $color, $font_name, $str[$i]);
			$x_pos = $x_pos + 35; // каждую следующую букву двигаем
		}
		return $img;
	};
	
	//генераци€ случайной строки
	function rc($count)
	{
		$chars="QWERTYUPASDFGHJKZXCVBNM"; //допустимые символы
		$str = "";		
		for ( $i = 0; $i < $count; $i++ )
		{
			$str = $str.$chars[rand(0, (strlen($chars)-1))];
		};
		return $str;
	}

	$str = rc(4);
	
	//запускаем
	session_start();
	//запоминаем сгенерированную строку в сессию
	$_SESSION['captcha_reg'] = $str;
	
	//генерируем капчу
	$captcha = create_capcha_image($str,"fonts/font_for_captcha.ttf","images/background_captcha.jpg");
	
	//выводим, страница это типа картинка :) очень удобно
	header('Expires: Sat, 31 May 2008 05:00:00 GMT'); 
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header('Cache-Control: no-store, no-cache, must-revalidate'); 
	header('Cache-Control: post-check=0, pre-check=0', FALSE); 
	header('Pragma: no-cache');  
	header("Content-Type: image/x-png");
	imagepng($captcha);

?>

<!--
ѕќ“ќ„Ќџ≈ Ў»‘–џ
–езультаты зарубежной открытой криптологии
-->
