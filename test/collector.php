<?php


$files = glob($_SERVER['argv'][1] . "/*.html");

$segments = explode('/', $_SERVER['argv'][1]);
$last = array_pop($segments);
$outputFile = implode('/', $segments) . "/{$last}.js";

$functions = array();
$functionBodies = array();

foreach ($files as $file)
{
	$content = file_get_contents($file);
	
	$first = "// expose test function names";
	
	$start = strpos($content, $first);
	$end   = strpos($content, "</script>", $start + strlen($first));


	$substring = substr($content, $start+$first, $end-$start);
	
	$function = substr($substring, strpos($substring, "/**"));
	
	$function = str_replace("function ", "", $function);
	$function = str_replace("() {", " : function () {", $function);
	//$function = preg_replace("/if\(checkInitialization\(builder, \"[a-zA-Z0-9_]+\"\) \!= null\) return;/", '', $function);
	
	// parse out the actual function
	array_push($functionBodies, trim($function));

	
}

//$imploded = implode("','", array_filter($functions));

/*$output = "// expose test function names
function exposeTestFunctionNames()
{
return ['{$imploded}'];
        
}


" . $functionBodies;
*/



file_put_contents($outputFile, "exports.tests = {\r\n".implode(",\r\n", array_filter($functionBodies)) . '}');