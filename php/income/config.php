<?
$config = array(
	'messages' => array(	// Сообщения об ошибках. Всегда следует избегать использования двойных кавычек. " => \"
		'email_exists' 		=> 'Введеный адрес email уже есть в базе данных',
		'no_email' 			=> 'Пожалуйста, введите адрес email',
		'email_invalid' 	=> 'Пожалуйста, введите правильный адрес email',
		'thank_you' 		=> 'Благодарим за ваш интереc!<br />Мы сообщим вам об обновлениях и других интересных событиях.<br />
                                           <!-- Так же, мы отправили Вам письмо, с приглашением на мини-игру. --> ',
		'technical' 		=> 'We are currently experiencing some technical difficulties. <br />Please try again later.'
	),
	'database' => array(	// Установки соединения с базой данных
		'host'				=> 'localhost',
		'username'			=> 'income',
		'password'			=> 'income',
		'database'			=> 'income'
	),
	'targetDate' => array(	// Дата для обратного отсчета
		'day'				=> 23,
		'month'				=> 10,
		'year'				=> 2012,
		'hour'				=> 10,
		'minute'			=> 00,
		'second'			=> 1
	)
);

$SmtpServer="127.0.0.1";
$SmtpPort="25"; //стандарт
$SmtpUser="";
$SmtpPass="";

?>
