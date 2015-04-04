<?

$curdir = dirname(__FILE__);

$doc = array();

$doc['security'] = array(
	'name' => 'Security',
	'description' => 'Methods for login, logout, registration and restore password.',
	'methods' => array(),
);
$doc['security']['methods'][] = json_decode(file_get_contents('security/login.json'), true);
$doc['security']['methods'][] = json_decode(file_get_contents('security/logout.json'), true);
$doc['security']['methods'][] = json_decode(file_get_contents('security/registration.json'), true);
$doc['security']['methods'][] = json_decode(file_get_contents('security/restore.json'), true);


$doc['updates'] = array(
	'name' => 'Updates',
	'description' => '?',
	'methods' => array(),
);
$doc['updates']['methods'][] = json_decode(file_get_contents('updates/install_updates.json'), true);

$doc['users'] = array(
	'name' => 'Users',
	'description' => '?',
	'methods' => array(),
);
$doc['users']['methods'][] = json_decode(file_get_contents('users/change_password.json'), true);
$doc['users']['methods'][] = json_decode(file_get_contents('users/delete.json'), true);
$doc['users']['methods'][] = json_decode(file_get_contents('users/export.json'), true);
$doc['users']['methods'][] = json_decode(file_get_contents('users/export_remove.json'), true);
$doc['users']['methods'][] = json_decode(file_get_contents('users/get.json'), true);
$doc['users']['methods'][] = json_decode(file_get_contents('users/get_ips.json'), true);
$doc['users']['methods'][] = json_decode(file_get_contents('users/insert.json'), true);
$doc['users']['methods'][] = json_decode(file_get_contents('users/list.json'), true);
$doc['users']['methods'][] = json_decode(file_get_contents('users/update.json'), true);
$doc['users']['methods'][] = json_decode(file_get_contents('users/update_events_last_id.json'), true);
$doc['users']['methods'][] = json_decode(file_get_contents('users/update_location.json'), true);
$doc['users']['methods'][] = json_decode(file_get_contents('users/update_logo.json'), true);
$doc['users']['methods'][] = json_decode(file_get_contents('users/update_nick.json'), true);
$doc['users']['methods'][] = json_decode(file_get_contents('users/update_password.json'), true);
$doc['users']['methods'][] = json_decode(file_get_contents('users/update_role.json'), true);
$doc['users']['methods'][] = json_decode(file_get_contents('users/update_status.json'), true);
$doc['users']['methods'][] = json_decode(file_get_contents('users/update_style.json'), true);
$doc['users']['methods'][] = json_decode(file_get_contents('users/upload_logo.json'), true);


$doc['games'] = array(
	'name' => 'Games',
	'description' => '?',
	'methods' => array(),
);
$doc['games']['methods'][] = json_decode(file_get_contents('games/choose.json'), true);
$doc['games']['methods'][] = json_decode(file_get_contents('games/delete.json'), true);
$doc['games']['methods'][] = json_decode(file_get_contents('games/get.json'), true);
$doc['games']['methods'][] = json_decode(file_get_contents('games/insert.json'), true);
$doc['games']['methods'][] = json_decode(file_get_contents('games/list.json'), true);
$doc['games']['methods'][] = json_decode(file_get_contents('games/scoreboard.json'), true);
$doc['games']['methods'][] = json_decode(file_get_contents('games/update.json'), true);
$doc['games']['methods'][] = json_decode(file_get_contents('games/update_score.json'), true);
$doc['games']['methods'][] = json_decode(file_get_contents('games/upload_logo.json'), true);

$doc['quests'] = array(
	'name' => 'Quests',
	'description' => '?',
	'methods' => array(),
);
$doc['quests']['methods'][] = json_decode(file_get_contents('quests/delete.json'), true);
$doc['quests']['methods'][] = json_decode(file_get_contents('quests/files_remove.json'), true);
$doc['quests']['methods'][] = json_decode(file_get_contents('quests/files_upload.json'), true);
$doc['quests']['methods'][] = json_decode(file_get_contents('quests/get.json'), true);
$doc['quests']['methods'][] = json_decode(file_get_contents('quests/get_all.json'), true);
$doc['quests']['methods'][] = json_decode(file_get_contents('quests/insert.json'), true);
$doc['quests']['methods'][] = json_decode(file_get_contents('quests/list.json'), true);
$doc['quests']['methods'][] = json_decode(file_get_contents('quests/pass.json'), true);
$doc['quests']['methods'][] = json_decode(file_get_contents('quests/take.json'), true);
$doc['quests']['methods'][] = json_decode(file_get_contents('quests/update.json'), true);
$doc['quests']['methods'][] = json_decode(file_get_contents('quests/user_answers.json'), true);

$doc['events'] = array(
	'name' => 'Events',
	'description' => '?',
	'methods' => array(),
);
$doc['events']['methods'][] = json_decode(file_get_contents('events/count.json'), true);
$doc['events']['methods'][] = json_decode(file_get_contents('events/delete.json'), true);
$doc['events']['methods'][] = json_decode(file_get_contents('events/get.json'), true);
$doc['events']['methods'][] = json_decode(file_get_contents('events/insert.json'), true);
$doc['events']['methods'][] = json_decode(file_get_contents('events/list.json'), true);
$doc['events']['methods'][] = json_decode(file_get_contents('events/types.json'), true);
$doc['events']['methods'][] = json_decode(file_get_contents('events/update.json'), true);

$doc['feedback'] = array(
	'name' => 'Feedback',
	'description' => '?',
	'methods' => array(),
);
$doc['feedback']['methods'][] = json_decode(file_get_contents('feedback/delete.json'), true);
$doc['feedback']['methods'][] = json_decode(file_get_contents('feedback/deletemessage.json'), true);
$doc['feedback']['methods'][] = json_decode(file_get_contents('feedback/get.json'), true);
$doc['feedback']['methods'][] = json_decode(file_get_contents('feedback/getmessage.json'), true);
$doc['feedback']['methods'][] = json_decode(file_get_contents('feedback/insert.json'), true);
$doc['feedback']['methods'][] = json_decode(file_get_contents('feedback/insertmessage.json'), true);
$doc['feedback']['methods'][] = json_decode(file_get_contents('feedback/list.json'), true);
$doc['feedback']['methods'][] = json_decode(file_get_contents('feedback/update.json'), true);
$doc['feedback']['methods'][] = json_decode(file_get_contents('feedback/updatemessage.json'), true);

$doc['settings'] = array(
	'name' => 'Settings',
	'description' => '?',
	'methods' => array(),
);
$doc['settings']['methods'][] = json_decode(file_get_contents('settings/get.json'), true);
$doc['settings']['methods'][] = json_decode(file_get_contents('settings/public_info.json'), true);
$doc['settings']['methods'][] = json_decode(file_get_contents('settings/types.json'), true);

$doc['statistics'] = array(
	'name' => 'Statistics',
	'description' => '?',
	'methods' => array(),
);
$doc['statistics']['methods'][] = json_decode(file_get_contents('statistics/answerlist.json'), true);
$doc['statistics']['methods'][] = json_decode(file_get_contents('statistics/list.json'), true);

/**
 * Indents a flat JSON string to make it more human-readable.
 * @param string $json The original JSON string to process.
 * @return string Indented version of the original JSON string.
 */
function indent($json) {

    $result      = '';
    $pos         = 0;
    $strLen      = strlen($json);
    $indentStr   = '  ';
    $newLine     = "\n";
    $prevChar    = '';
    $outOfQuotes = true;

    for ($i=0; $i<=$strLen; $i++) {

        // Grab the next character in the string.
        $char = substr($json, $i, 1);

        // Are we inside a quoted string?
        if ($char == '"' && $prevChar != '\\') {
            $outOfQuotes = !$outOfQuotes;

        // If this character is the end of an element,
        // output a new line and indent the next line.
        } else if(($char == '}' || $char == ']') && $outOfQuotes) {
            $result .= $newLine;
            $pos --;
            for ($j=0; $j<$pos; $j++) {
                $result .= $indentStr;
            }
        }

        // Add the character to the result string.
        $result .= $char;

        // If the last character was the beginning of an element,
        // output a new line and indent the next line.
        if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
            $result .= $newLine;
            if ($char == '{' || $char == '[') {
                $pos ++;
            }

            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
        }

        $prevChar = $char;
    }

    return $result;
}

function convert_to_html($doc) {
	$result = '';
	$result .= '<table cellspacing=1px cellpadding=10px bgcolor=black>';
	
	$result .= '
		<tr>
			<td colspan=4 bgcolor=white><h1>API</h1>
				This chapter will talk about what external functions exist to work with<br>
				the system. Also presented are various examples for the job. Also,<br>
				this chapter is devoted to the frontend developers using kernel of fhq.<br>
				<br>
				Also sometimes you will be need captcha: api/captcha.php
			</td>
		</tr>
	';

	$i = 0;
	foreach ($doc as $section_key => $section)
	{
		$i++;
		$result .= '
				<tr>
					<td colspan=4 bgcolor=white><h2>'.$i.' '.$section['name'].'</h2>'.$section['description'].'</td>
				</tr>
		';

		$result .= '
				<tr>
					<td bgcolor=white><b>Name</b></td>
					<td bgcolor=white><b>Input parameters</b></td>
					<td bgcolor=white><b>Successfully response</b></td>
					<td bgcolor=white><b>Code Errors</b></td>
				</tr>
		';
		$i1 = 0;
		foreach ($section['methods'] as $method_key => $method)
		{
			$i1++;
			$name = '<h3>'.$i.'.'.$i1.' '.$method['name'].'</h3><pre>'.$method['description'].'</pre>Path: <pre>'.$method['uri'].'</pre>'.
			'This function access for '.$method['access'];
			$input = '';
			$response = '<pre>'.indent(json_encode($method['output']['successfull'])).'</pre>';
			$codeerrors = '';
			
			foreach ($method['input'] as $input_key => $input_p)
			{
				$input .= '<b>'.$input_key.'</b> - '.$input_p['type'].', '.$input_p['description'].'<br>';
			};
			
			foreach ($method['output']['errors'] as $error_key => $error)
				$codeerrors .= "<b>".$error_key."</b> ".$error.'<br/>';
			
			$result .= '
				<tr>
					<td valign=top bgcolor=white>'.$name.'</td>
					<td valign=top bgcolor=white>'.$input.'</td>
					<td valign=top bgcolor=white>'.$response.'</td>
					<td valign=top bgcolor=white>'.$codeerrors.'</td>
				</tr>
			';
		}
	}
	$result .= '</table>';
	return $result;
}

function print_doc($doc) {
	if (isset($_GET['json'])) {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		echo json_encode($doc);
	} else if (isset($_GET['html'])) {
		echo convert_to_html($doc);
	} else {
		echo "<a href='?html'>HTML</a> <a href='?json'>JSON</a>";
	}
}

print_doc($doc);
