<?

$curdir = dirname(__FILE__);

function hasSubString($line, $substring) {
	$pos = strpos($line, $substring);
	return ($pos !== false);
}

function getSubString($line, $substring) {
	$pos = strpos($line, $substring);
	if ($pos !== false) {
		return substr($line, $pos + strlen($substring));
	}
	return '';
}

function parseErrorCode($line) {
	$pos = strpos($line, 'APIHelpers::showerror');
	$str = substr($line, $pos + strlen('APIHelpers::showerror'));
	$pos = strpos($str, '(');
	$str = substr($str, $pos);
	$str = substr($str, 1, strpos($str, ',') - 1);
	return $str;
}

function parseErrorMessage($line) {
	$pos = strpos($line, 'APIHelpers::showerror');
	$str = substr($line, $pos + strlen('APIHelpers::showerror'));
	$pos = strpos($str, '(');
	$str = substr($str, $pos);
	$str = substr($str, strpos($str, ',') + 1);
	if (strpos($str, '\'') !== false) {
		$str = substr($str, strpos($str, '\''));
		$str = substr($str, 1, strpos($str, '\');')  - 1);
	} else {
		$str = substr($str, 1, strpos($str, ');')  - 1);
		if ($str == '$e->getMessage()') {
			return 'Errors in database';
		}
	}
	return $str;
}

function parseInputName($line) {
	$pos = strpos($line, 'API_INPUT:');
	$str = substr($line, $pos + strlen('API_INPUT:'));
	$str = substr($str, 1, strpos($str, '-') - 1);
	return $str;
}

function parseInputType($line) {
	$pos = strpos($line, 'API_INPUT:');
	$str = substr($line, $pos + strlen('API_INPUT:'));
	$str = substr($str, strpos($str, '-'));
	$str = substr($str, 1, strpos($str, ',') - 1);
	return $str;
}

function parseInputDescription($line) {
	$pos = strpos($line, 'API_INPUT:');
	$str = substr($line, $pos + strlen('API_INPUT:'));
	$str = substr($str, strpos($str, '-'));
	$str = substr($str, strpos($str, ',') + 1);
	return $str;
}

function scanfolder($dir) {
	$result = array();

	$files = scandir($dir, 1);
	sort($files);
	foreach ($files as $key => $value)
	{
		if ($value != 'index.php' && strpos($value, '.php') !== false)  {
			$method = array(
				'name' => '',
				'description' => '',
				'uri' => 'api/'.$dir.'/'.$value,
				'access' => '?',
				'input' => array(),
				'output' => array(
					'errors' => array(
					),				
				),
			);
			// echo $key.' => '.$value.' <br>';
			
			$handle = fopen($dir.'/'.$value, "r");
			if ($handle) {
				while (($line = fgets($handle)) !== false) {
					
					if (hasSubString($line, 'API_NAME:')) {
						$method['name'] = getSubString($line, 'API_NAME:');
					}
					if (hasSubString($line, 'API_DESCRIPTION:')) {
						$method['description'] .= getSubString($line, 'API_DESCRIPTION:');
					}
					if (hasSubString($line, 'API_ACCESS:')) {
						$method['access'] = getSubString($line, 'API_ACCESS:');
					}
					
					if (hasSubString($line, 'API_INPUT:')) {
						$method['input'][parseInputName($line)] = array(
							'type' => parseInputType($line),
							'description' => parseInputDescription($line),
						);
					}
					
					if (hasSubString($line, 'APIHelpers::showerror')) {
						$method['output']['errors'][parseErrorCode($line)] = parseErrorMessage($line);
					}
				}
				fclose($handle);
			} else {
				// error opening the file.
			} 

			
			$result[] = $method;
		}
	}
	return $result;
};

$doc = array();

$doc['security'] = array(
	'name' => 'Security',
	'description' => 'Methods for login, logout, registration and restore password.',
	'methods' => scanfolder('security'),
);

$doc['updates'] = array(
	'name' => 'Updates',
	'description' => 'Methods for update database',
	'methods' => scanfolder('updates'),
);

$doc['users'] = array(
	'name' => 'Users',
	'description' => 'Methods for work with users',
	'methods' => scanfolder('users'),
);

$doc['games'] = array(
	'name' => 'Games',
	'description' => 'Methods for work with games',
	'methods' => scanfolder('games'),
);

$doc['quests'] = array(
	'name' => 'Quests',
	'description' => 'Methods for work with quests',
	'methods' => scanfolder('quests'),
);

$doc['events'] = array(
	'name' => 'Events',
	'description' => 'Events/News from the system or by admin',
	'methods' => scanfolder('events'),
);

$doc['feedback'] = array(
	'name' => 'Feedback',
	'description' => 'Methods for work with feedback',
	'methods' => scanfolder('feedback'),
);

$doc['settings'] = array(
	'name' => 'Settings',
	'description' => 'Methods for get settings, public info, and enums',
	'methods' => scanfolder('settings'),
);


$doc['statistics'] = array(
	'name' => 'Statistics',
	'description' => 'Methods for work with statistics',
	'methods' => scanfolder('statistics'),
);

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
					<td colspan=3 bgcolor=white><h2>'.$i.' '.$section['name'].'</h2>'.$section['description'].'</td>
				</tr>
		';

		$result .= '
				<tr>
					<td bgcolor=white><b>Name</b></td>
					<td bgcolor=white><b>Input parameters</b></td>
					<td bgcolor=white><b>Code Errors</b></td>
				</tr>
		';
		$i1 = 0;
		foreach ($section['methods'] as $method_key => $method)
		{
			$i1++;
			$name = '<h3>'.$i.'.'.$i1.' '.$method['name'].'</h3>'.
			'This function access for '.$method['access'].'.<br>'.
			'Description:<pre>'.$method['description'].'</pre>Path: <pre>'.$method['uri'].'</pre>'
			;
			$input = '';
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
