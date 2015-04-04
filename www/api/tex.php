<?

/**
 * Indents a flat JSON string to make it more human-readable.
 *
 * @param string $json The original JSON string to process.
 *
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
  $result = "
     <h1>API</h1>
     This chapter will talk about what external functions exist to work with<br>
     the system. Also presented are various examples for the job. Also,<br>
     this chapter is devoted to the frontend developers using kernel of fhq.<br>
     <br>
     Also sometimes you will be need captcha: api/captcha.php
  ";
	
	foreach ($doc as $section_key => $section)
	{
		$result .= '<h2>'.$section['name'].'</h2>'.$section['description'].' <br><br>';
		$result .= '
			<table cellspacing=1px cellpadding=10px bgcolor=black>
				<tr>
					<td bgcolor=white><b>Name</b></td>
					<td bgcolor=white><b>Input parameters</b></td>
					<td bgcolor=white><b>Successfully response</b></td>
					<td bgcolor=white><b>Code Errors</b></td>
				</tr>
		';
		foreach ($section['methods'] as $method_key => $method)
		{
			$name = '<h3>'.$method['name'].'</h3><pre>'.$method['description'].'</pre>Path: <pre>'.$method['uri'].'</pre>'.
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
		$result .= '</table>';
	}

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
