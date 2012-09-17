<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET'){
  print $_GET['hub_challenge'];
  // var_dump($_GET);
} else {

$instagram_update = file_get_contents('php://input');


//generate log
ob_start();
var_dump($instagram_update);
$output = ob_get_contents();
ob_end_clean();

$file = 'instagram_log.txt';
$current = file_get_contents($file);
$current .= "\n=====================\n";
$current .= $output;
$current .= "\n---------------------\n";
file_put_contents($file, $current);



//update db

} ?>