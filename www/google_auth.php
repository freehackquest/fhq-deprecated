<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/config/config.php';
require_once __DIR__.'/api/api.lib/api.base.php';
require_once __DIR__.'/api/api.lib/api.helpers.php';
require_once __DIR__.'/api/api.lib/api.security.php';
require_once __DIR__.'/api/api.lib/api.user.php';

$google_client = new Google_Client();
$google_client->setApplicationName($config['google_auth']['appname']);
$google_client->setClientId($config['google_auth']['client_id']);
$google_client->setClientSecret($config['google_auth']['client_secret']);
$google_client->setRedirectUri("postmessage"); /* alway postmessage. */
$google_client->setDeveloperKey($config['google_auth']['developer_key']);

$google_client->addScope(array('https://www.googleapis.com/auth/userinfo.email',
		'https://www.googleapis.com/auth/userinfo.profile',
        'https://www.googleapis.com/auth/plus.me'));

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
	$google_client->setAccessToken($_SESSION['access_token']);
	$_SESSION['access_token'] = $google_client->getAccessToken();
	$plus = new Google_Service_Plus($google_client);
	try{
		$me = $plus->people->get("me"); /* me is the current logged in user */
		$email = $me['emails'][0]['value'];
		$image = isset($me['image']) ? $me['image']['url'] : "";
		
		$_SESSION["EMAIL"] = $email; /* keep in session variable whatever you send to client */
		
		$conn = APIHelpers::createConnection($config);
		if(APISecurity::login_by_google($conn, $email)){
			APIHelpers::$TOKEN = APIHelpers::gen_guid();
			$result['data']['token'] = APIHelpers::$TOKEN;
			$result['data']['session'] = APIHelpers::$FHQSESSION;
			APISecurity::updateLastDTLogin($conn);
			APIUser::loadUserProfile($conn);
			// APIUser::loadUserScore($conn);
			APISecurity::saveByToken();
			unset($_SESSION['access_token']);
			header('Location: ./main.php');
		}else{
			$nick = "hacker-".substr(md5(rand().rand()), 0, 7);
			$email = strtolower($email);
			$uuid = APIHelpers::gen_guid();

			$password = substr(md5(rand().rand()), 0, 8);
			$password_hash = APISecurity::generatePassword2($email, $password);

			// same code exists in api/users/insert.php
			// same code exists in google_auth.php
			$query = '
					INSERT INTO users(
							uuid,
							pass,
							status,
							email,
							nick,
							role,
							logo,
					last_ip,
							dt_last_login,
							dt_create
					)
					VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, NOW(),NOW());
			';

			$stmt_insert = $conn->prepare($query);

			$new_user = array(
				$uuid,
				$password_hash, // pass
				'activated',
				$email,
				$nick,
				'user',
				'files/users/0.png',
				''
			);
			
			APIEvents::addPublicEvents($conn, 'users', 'New player {'.htmlspecialchars($nick).'}. Welcome!');

			$r = $stmt_insert->execute($new_user);
			if(APISecurity::login_by_google($conn, $email)){
				APIHelpers::$TOKEN = APIHelpers::gen_guid();
				$result['data']['token'] = APIHelpers::$TOKEN;
				$result['data']['session'] = APIHelpers::$FHQSESSION;
				APISecurity::updateLastDTLogin($conn);
				APIUser::loadUserProfile($conn);
				// APIUser::loadUserScore($conn);
				APISecurity::saveByToken();
				unset($_SESSION['access_token']);
				header('Location: ./main.php');
			}else{
				APIEvents::addPublicEvents($conn, 'errors', 'Alert! Admin, google registration is broken!');
				error_log("1287: ".$error);
				APIHelpers::showerror(1287, '[Registration] Sorry registration is broken. Please send report to the admin about this.');
			}

		}
	}catch(Exception $e){
		echo $e->getMessage();
	}
} else {
	$redirect_uri = $config['google_auth']['oauth2google_uri'];
	header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
