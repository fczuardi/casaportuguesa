<?php
ini_set('display_errors', '0');
$db = "fotos.sqlite";
$tag = $_GET['tag'];
if(is_null($tag)) {
  $tag = "itatibafoo";
}
$tags = array('umacasaportuguesacomcerteza', 'casalusa');
if (in_array($tag, $tags)){
  $db_table_name = 'casalusa';
} else{
  $db_table_name = 'itatibafoo';
}
$min_tag_id_filename = "$db_table_name.min_tag_id.txt";
$page = $_GET['page'];
if(is_null($page)) {
  $page = 1;
}
$client_id = file_get_contents("clientID.txt");
if ($_GET['partial'] == 'yes'){
  $min_tag_id = file_get_contents($min_tag_id_filename);
}else{
  $min_tag_id = '';
}

$next_url = $_GET['next_url'];
if(is_null($next_url)) {
  $next_url = "https://api.instagram.com/v1/tags/$tag/media/recent?client_id=$client_id&min_tag_id=$min_tag_id";
}


$handle = sqlite_open($db) or die("Could not open database".sqlite_error_string(sqlite_last_error($handle)));
$q = "CREATE TABLE $db_table_name (
    id INTEGER PRIMARY KEY,
    username TEXT,
    link TEXT,
    likes_count int,
    image_url TEXT,
    created_time DATETIME,
    featured INTEGER,
    featured_time DATETIME,
    photo_id TEXT UNIQUE,
    user_id TEXT
    )";
$ok = sqlite_exec($handle, $q, $error);

function updateDB($instagram_response, $handle, $db_table_name){
    $results = json_decode($instagram_response, true);

    foreach ($results["data"] as $photo_data) {
      $username = sqlite_escape_string($photo_data["user"]["username"]);
      $link = sqlite_escape_string($photo_data["link"]);
      $likes_count = $photo_data["likes"]["count"];
      $image_url = sqlite_escape_string($photo_data["images"]["low_resolution"]["url"]);
      $created_time = strftime("%Y-%m-%d %X",$photo_data["created_time"]);//yyyy-MM-dd HH:mm:ss
      $created_time = "datetime(" . $photo_data["created_time"] . ", 'unixepoch')";
      $photo_id = sqlite_escape_string($photo_data["id"]);
      $user_id = sqlite_escape_string($photo_data["user"]["id"]);
      $q = "INSERT OR REPLACE INTO
                    $db_table_name(  id, featured, featured_time,
                    username,
                    link,
                    likes_count,
                    image_url,
                    created_time,
                    photo_id,
                    user_id)
            VALUES((select id from $db_table_name where photo_id = '$photo_id') ,
                   (select featured from $db_table_name where photo_id = '$photo_id') ,
                   (select featured_time from $db_table_name where photo_id = '$photo_id') ,
                    '$username',
                    '$link',
                    '$likes_count',
                    '$image_url',
                    $created_time,
                    '$photo_id',
                    '$user_id');";
      $ok = sqlite_exec($handle, $q, $error);
      if(!$ok){
      echo "<hr>";
        var_dump($q);
      die();
      }
    };
}

  $instagram_response = file_get_contents($next_url);
  if ($instagram_response){
    updateDB($instagram_response, $handle, $db_table_name);
    try{
      $results = json_decode($instagram_response, true);
      if ( ($_GET['partial'] == 'yes') && ( $page == 1) ){
        $min_tag_id = $results["pagination"]["min_tag_id"];
        file_put_contents($min_tag_id_filename, $min_tag_id);
        $next_url = NULL;
      } else{
        $next_url = $results["pagination"]["next_url"];
        $instagram_response = file_get_contents($next_url);
      }
    }catch(Exception $e){
      continue;
    }
    if (!is_null($next_url)){
      $page +=1;
      ?>
<html>
<head>
  <meta http-equiv="refresh" content="2;URL='?<?php echo "page=$page&tag=$tag&next_url=" . urlencode($next_url);?>'">
</head>
<body>
  <pre>
  Calculando a proxima pagina(<?php echo $page;?>) em 2 segundos...
  <?php echo "$next_url"; ?>
  </pre>
</body>
</html>
      <?php
    }else{
      echo "the end";
    }
  }
sqlite_close($handle);
?>