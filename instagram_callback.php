
<?php

ob_start();

var_dump($_REQUEST);
var_dump(file_get_contents('php://input'));

$output = ob_get_contents();

ob_end_clean();

echo $output;

$file = 'instagram_log.txt';
// Open the file to get existing content
$current = file_get_contents($file);
// Append a new person to the file
$current .= "\n=====================\n";
$current .= $output;
$current .= "\n---------------------\n";
// Write the contents back to the file
file_put_contents($file, $current);
?>