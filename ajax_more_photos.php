<?php
$page = $_GET["page"];
$page_size = 28;
$page_begin = ($page-1) * $page_size;
$page_end = $page_begin + ($page_size-1);
$db = "fotos.sqlite";
$db_table_name = 'itatibafoo';
$handle = sqlite_open($db) or die("Could not open database".sqlite_error_string(sqlite_last_error($handle)));
$q = "SELECT * FROM $db_table_name ORDER BY created_time DESC LIMIT $page_begin, $page_size";
$query = sqlite_query($handle, $q);
$result = sqlite_fetch_all($query, SQLITE_ASSOC);
echo "<ol>";
foreach ($result as $entry) {
  echo '<li><a href="' . $entry["link"] . '"><img src="'
                       . $entry["image_url"] . '"></img></a></li>';
}
echo "</ol>";
?>
