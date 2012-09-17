<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET'){
  print $_GET['hub_challenge'];
  // var_dump($_GET);
} else {

$instagram_update = file_get_contents('php://input');


//generate log
ob_start();
var_dump($instagram_update);
echo "\n";

//update db
$results = json_decode($instagram_update, true);
var_dump($results);
$current_url = "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];

if (is_array($results) ){
  foreach ($results as $result) {
    $tag = $result["object_id"];
    $update_url = substr($current_url, 0, (strrpos($current_url, "/")) ) . "/update_picture_list.php?tag=$tag&partial=yes";
    echo "opening $update_url...\n";
    $request = file_get_contents($update_url);
    var_dump($request);
    echo "\n";
  }
}




$output = ob_get_contents();
ob_end_clean();

$file = 'instagram_log.txt';
$current = file_get_contents($file);
$current .= "\n=====================\n";
$current .= $output;
$current .= "\n---------------------\n";
file_put_contents($file, $current);



} ?>