<?php
ini_set('display_errors', '0');

   $mtime = microtime();
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $starttime = $mtime;

$db = "fotos.sqlite";
$tags = array('umacasaportuguesacomcerteza', 'casalusa');
$db_table_name = 'casalusa';
$db_users_table_name = 'casalusa_users';
$client_id = file_get_contents("clientID.txt");
$photos_to_remove = array();

$handle = sqlite_open($db) or die("Could not open database".sqlite_error_string(sqlite_last_error($handle)));

$requests_count = 0;

function updateDB($instagram_response, $handle, $db_table_name, $db_users_table_name){
  global $photos_to_remove;
    $results = json_decode($instagram_response, true);

    foreach ($results["data"] as $photo_data) {
      $username = sqlite_escape_string($photo_data["user"]["username"]);
      $user_website = sqlite_escape_string($photo_data["user"]["website"]);
      $user_bio = sqlite_escape_string($photo_data["user"]["bio"]);
      $user_profile_picture = sqlite_escape_string($photo_data["user"]["profile_picture"]);
      $user_full_name = sqlite_escape_string($photo_data["user"]["full_name"]);
      $user_id = sqlite_escape_string($photo_data["user"]["id"]);

      $link = sqlite_escape_string($photo_data["link"]);
      $likes_count = $photo_data["likes"]["count"];
      $image_url = sqlite_escape_string($photo_data["images"]["low_resolution"]["url"]);
      $created_time = strftime("%Y-%m-%d %X",$photo_data["created_time"]);//yyyy-MM-dd HH:mm:ss
      $created_time = "datetime(" . $photo_data["created_time"] . ", 'unixepoch')";
      $photo_id = sqlite_escape_string($photo_data["id"]);
      $user_id = sqlite_escape_string($photo_data["user"]["id"]);
      if(!is_null($photo_data["link"])){
        $q = "INSERT OR REPLACE INTO
                      $db_table_name(  id, featured, featured_time, blacklisted_photo,
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
                     (select blacklisted_photo from $db_table_name where photo_id = '$photo_id') ,
                      '$username',
                      '$link',
                      '$likes_count',
                      '$image_url',
                      $created_time,
                      '$photo_id',
                      '$user_id');";
        $ok = sqlite_exec($handle, $q, $error);
        if(!$ok){
          echo "\nSQLITE-ERROR-----------------------
                \n$q
                \n$error
                \n-----------------------------------\n";
        }

        $q = "INSERT OR REPLACE INTO
                      $db_users_table_name(id, blocked, website, bio, profile_picture, full_name, user_id, username)
              VALUES( (select id from $db_users_table_name where user_id = '$user_id') ,
                      (select blocked from $db_users_table_name where user_id = '$user_id') ,
                      '$user_website',
                      '$user_bio',
                      '$user_profile_picture',
                      '$user_full_name',
                      '$user_id',
                      '$username'
                    );";
        $ok = sqlite_exec($handle, $q, $error);
        if(!$ok){
          echo "\nSQLITE-ERROR-----------------------
                \n$q
                \n$error
                \n-----------------------------------\n";
        }
      }else{
        array_push($photos_to_remove, $photo_id);
          echo "\nINSTAGRAM-ERROR-LINK-NULL----------
                \nPhoto: $photo_id
                \n-----------------------------------\n";
      }
    };
}

foreach ($tags as $tag) {
  $next_url = "https://api.instagram.com/v1/tags/$tag/media/recent?client_id=$client_id";
  do{
    $requests_count++;
    echo "\n$requests_count. Opening $next_url";
    $instagram_response = file_get_contents($next_url);
    if ($instagram_response){
      echo "\ngot response: length=".strlen($instagram_response);
      updateDB($instagram_response, $handle, $db_table_name, $db_users_table_name, $photos_to_remove);
      try{
        $results = json_decode($instagram_response, true);
        $next_url = $results["pagination"]["next_url"];
      }catch(Exception $e){
        echo "\nPHP-INSTAGRAM-RESPONSE-ERROR-------
              \n$e
              \n$results
              \n$instagram_response
              \n-----------------------------------\n";
        continue;
      }
    }else{
        echo "\nPHP-INSTAGRAM-RESPONSE-ERROR-------
              \n$instagram_response
              \n-----------------------------------\n";
    }
    // $nano = time_nanosleep(0, 500000000);//sleep for half-second
  } while (!is_null($next_url));
}

sqlite_close($handle);

   $mtime = microtime();
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $endtime = $mtime;
   $totaltime = ($endtime - $starttime);
   echo "\nFinished in ".$totaltime." seconds\n\n";
   echo "\nPhotos to be unlisted: '". implode("','", $photos_to_remove) . "'\n";

?>