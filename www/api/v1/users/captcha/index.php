<?php
/*
 * sea-kg: if not working: try for debian "sudo apt-get install php5-gd" and than restart apache
 * 
 * API_NAME: Captcha
 * API_DESCRIPTION: Method will be returned captcha image
 * API_ACCESS: any users
 * API_INPUT: token - string, token
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../../../api.lib/api.base.php");
include_once ($curdir."/../../../api.lib/api.helpers.php");
include_once ($curdir."/../../../../config/config.php");

$response = APIHelpers::startpage($config);
$conn = APIHelpers::createConnection($config);

function create_capcha_image($str, $font_name, $backgraund_png)
{
	$img = imagecreatefrompng($backgraund_png); // 89?30 px
	// imagealphablending($img, false);
	imagesavealpha($img, true);

	// $color = imagecolorallocate( $img, 0, 0, 0 );
	$color = imagecolorallocate( $img, 159, 159, 159 );
	$str_arr = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
	$font_size = 30;
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

$captcha_uuid = APIHelpers::gen_guid();
$captcha_val = rc(4);

$conn->prepare('DELETE FROM users_captcha WHERE dt_expired < NOW()')->execute();

$stmt = $conn->prepare('INSERT INTO users_captcha(captcha_uuid, captcha_val, dt_expired) VALUES(?, ?, NOW() + INTERVAL 1 HOUR);');
$stmt->execute(array($captcha_uuid, $captcha_val));

$captcha = create_capcha_image($captcha_val,'./Bleeding_Cowboys.ttf','./background.png');
$tmpfilename = tempnam('/tmp', $captcha_val);
imagepng($captcha, $tmpfilename, 9);
$response['data']['captcha'] = base64_encode(file_get_contents($tmpfilename));
$response['data']['uuid'] = $captcha_uuid;
unlink($tmpfilename);
imagedestroy($captcha);
$response['result'] = 'ok';
$response['httpcode'] = 200;
APIHelpers::endpage($response);

