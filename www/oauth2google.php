<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/config/config.php';

session_start();

$google_client = new Google_Client();
$google_client->setApplicationName($config['google_auth']['appname']);
$google_client->setClientId($config['google_auth']['client_id']);
$google_client->setClientSecret($config['google_auth']['client_secret']);
$google_client->setRedirectUri("postmessage"); /* alway postmessage. */
$google_client->setDeveloperKey($config['google_auth']['developer_key']);
$google_client->setRedirectUri($config['google_auth']['oauth2google_uri']);

$google_client->addScope(array(
	'https://www.googleapis.com/auth/userinfo.email',
	'https://www.googleapis.com/auth/userinfo.profile',
	'https://www.googleapis.com/auth/plus.me'
));

if (! isset($_GET['code'])) {
  $auth_url = $google_client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
  $google_client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $google_client->getAccessToken();
  $redirect_uri = $config['google_auth']['google_auth_uri'];
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
