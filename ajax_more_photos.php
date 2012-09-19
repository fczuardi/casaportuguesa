<?php
$page = $_GET["page"];
$page_size = 28;
$page_begin = ($page-1) * $page_size;
$page_end = $page_begin + ($page_size-1);
$db = "fotos.sqlite";
// $db_table_name = 'itatibafoo';
$db_table_name = 'casalusa';
$db_users_table_name = 'casalusa_users';
$handle = sqlite_open($db) or die("Could not open database".sqlite_error_string(sqlite_last_error($handle)));

//query for blacklisted users
$q = "SELECT * FROM $db_users_table_name WHERE blocked IS NOT NULL";
$query = sqlite_query($handle, $q);
$blacklisted_users_results = sqlite_fetch_all($query, SQLITE_ASSOC);
$blacklisted_users = array();
if (count($blacklisted_users_results) > 0){
  foreach ($blacklisted_users_results as $user){
    array_push($blacklisted_users, $user["user_id"]);
  }
}
$blacklisted_users_list = "'" . implode("','", $blacklisted_users) . "'";
if ($_GET["admin"] == yes){
  $q = "SELECT * FROM $db_table_name ORDER BY created_time DESC LIMIT $page_begin, $page_size";
}else{
  $q = "SELECT * FROM $db_table_name WHERE
        blacklisted_photo IS NULL AND
        user_id NOT IN($blacklisted_users_list)
        ORDER BY created_time DESC LIMIT $page_begin, $page_size";
}
$query = sqlite_query($handle, $q);
$result = sqlite_fetch_all($query, SQLITE_ASSOC);

echo "<ol>";
foreach ($result as $entry) {
  if ($_GET["admin"] == yes){
  echo '<li data-username="'.$entry["username"].
            '" data-photo_id="'.$entry["photo_id"].
            '" data-featured="'.$entry["featured"].
            '" data-user_id="'.$entry["user_id"].
            '">
          <a href="' . $entry["link"] . '"><img src="'
                     . $entry["image_url"] . '"></img></a>
          <fieldset>
            <p class="username-label">' . $entry["username"] . '</p>
            <label class="photo-id"><textarea>' . $entry["photo_id"] . '</textarea>
            <span>
              <i class="destaque-icon"></i>
              <i class="blacklist-photo-icon"></i>
              <input type="checkbox" name="blacklisted-photos[' . $entry["photo_id"] . ']" '.
              (($entry["blacklisted_photo"] == '1')?'checked=checked':'') . '/>
              <i class="blacklist-user-icon"></i>
              <input class="block-user" type="checkbox" name="blacklisted-users[' . $entry["user_id"] . ']" '.
              ((in_array($entry["user_id"], $blacklisted_users))?'checked=checked':'') . '/>
            </span>
          </fieldset>
        </li>';
  }else{
    echo '<li><a href="' . $entry["link"] . '"><img src="'
                         . $entry["image_url"] . '"></img></a></li>';
  }
}
echo "</ol>";
?>
