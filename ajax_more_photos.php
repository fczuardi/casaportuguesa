<?php
$page = $_GET["page"];
$page_size = 28;
$page_begin = ($page-1) * $page_size;
$page_end = $page_begin + ($page_size-1);
$db = "fotos.sqlite";
// $db_table_name = 'itatibafoo';
$db_table_name = 'casalusa';
$handle = sqlite_open($db) or die("Could not open database".sqlite_error_string(sqlite_last_error($handle)));
$q = "SELECT * FROM $db_table_name WHERE blacklisted_photo IS NULL ORDER BY created_time DESC LIMIT $page_begin, $page_size";
if ($_GET["admin"] == yes){
$q = "SELECT * FROM $db_table_name ORDER BY created_time DESC LIMIT $page_begin, $page_size";
}
$query = sqlite_query($handle, $q);
$result = sqlite_fetch_all($query, SQLITE_ASSOC);
echo "<ol>";
foreach ($result as $entry) {
  if ($_GET["admin"] == yes){
  echo '<li data-username="'.$entry["username"].'" data-photo_id="'.$entry["photo_id"].'" data-featured="'.$entry["featured"].'">
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
              <input type="checkbox" name="blacklisted-users[' . $entry["username"] . ']" />
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
