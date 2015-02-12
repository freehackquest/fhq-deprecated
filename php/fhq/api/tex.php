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
     This chapter will talk about what external functions exist to work with
     the system. Also presented are various examples for the job. Also,
     this chapter is devoted to the frontend developers using kernel of fhq.	
     <br>
  ";
	
	foreach ($doc as $section_key => $section)
	{
		$result .= "
      <h2>".$section['name']."</h2>
      ".$section['description']." <br>
    ";

    if (isset($section['uri'])) {
      $result .= "
        <pre>".$section['uri']."</pre><br>
      ";
    }

		foreach ($section['methods'] as $method_key => $method)
		{
			
			$result .= "
        <h3>".$method['name']."</h3>
        ".$method['description']." <br>
        This function access for ".$method['access'].".<br>
URI:
<pre>
".$method['uri']."
</pre>
<br>
Input parameters (GET or POST):
<ul>";
			foreach ($method['input'] as $input_key => $input)
			{
				$result .= "
          <li> <b>".$input_key."</b> - ".$input['type'].", ".$input['description']."</li>";

			};
			$result .= "
</ul>

        Successfully response:
        <pre>
        ".indent(json_encode($method['output']['successfull']))."
        </pre>
      <br>
      Code errors: <ul>";

			foreach ($method['output']['errors'] as $error_key => $error)
				$result .= "<li> <b>".$error_key."</b> ".$error." </li>";
      $result .= "</ul>";
		}
		
	}

	return $result;
}

function convert_to_tex($doc) {
	$result = "
\\chapter{API}
This chapter will talk about what external functions exist to work with
the system. Also presented are various examples for the job. Also,
this chapter is devoted to the frontend developers using kernel of fhq.	
";
	
	foreach ($doc as $section_key => $section)
	{
		$result .= "
\\newpage
\\section{".$section['name']."}
".$section['description']." \\\\
";

    if (isset($section['uri'])) {
      $result .= "
URI:
\\begin{Verbatim}[frame=single]
".$section['uri']."
\\end{Verbatim}
";
    }

		foreach ($section['methods'] as $method_key => $method)
		{
			
			$result .= "

\\subsection{".$method['name']."}
\\par
".$method['description']." \\\\
This function access for ".$method['access'].".\\\\

URI:
\\begin{Verbatim}[frame=single]
".$method['uri']."
\\end{Verbatim}

Input parameters (GET or POST):
\\begin{itemize}";
			foreach ($method['input'] as $input_key => $input)
			{
				 // TODO encode _ => \_
				$result .= "
  \\item \\textbf{".$input_key."} - ".$input['type'].", ".$input['description']."";

			};
			$result .= "
\\end{itemize}

Successfully response:
\\begin{Verbatim}[frame=single]
".indent(json_encode($method['output']['successfull']))."
\\end{Verbatim}

Code errors:
\\begin{itemize}";

			foreach ($method['output']['errors'] as $error_key => $error)
			{
				$result .= "
  \\item \\textbf{".$error_key."} - ".$error."";
			}

$result .= "
\\end{itemize}
";
		}
		
	}

	return $result;
}

function print_doc($doc) {
	
	if (isset($_GET['tex'])) {
		header('Content-Type: text');
		echo convert_to_tex($doc);
	} else if (isset($_GET['json'])) {
		header('Content-Type: text');
		echo json_encode($doc);
	} else if (isset($_GET['html'])) {
		echo convert_to_html($doc);
	} else {
		echo "<a href='?html'>HTML</a> <a href='?json'>JSON</a> <a href='?tex'>LaTeX</a>";
	}
}
