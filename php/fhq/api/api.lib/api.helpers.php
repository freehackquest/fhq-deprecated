<?

function showerror($code, $message) {
  $result = array(
	  'result' => 'fail',
  	'data' => array(),
  );
 	$result['error']['code'] = $code;
	$result['error']['message'] = $message;
  echo json_encode($result);
  exit;
}

function checkAuth($security)
{
  if(!$security->isLogged())
	{
		refreshTo("index.php");
		return;
	};

  if(!$auth->isLogged()) {
    $result = array(
  	  'result' => 'fail',
    	'data' => array(),
    );
   	$result['error']['code'] = "403";
    $result['error']['message'] = 'Error 403: Not authorized request';
    echo json_encode($result);
    exit;
  }
}