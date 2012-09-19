<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Uma casa portuguesa com certeza</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css?v=1345764311310">
    <link rel="stylesheet" href="css/admin.css?v=1345764311310">
    <script src="js/vendor/modernizr-2.6.1.min.js"></script>
    <!--
    <script type="text/javascript" src="js/vendor/live.js"></script>
  -->
  </head>
<?php
// ini_set('display_errors', '1');

$page = 1;
$page_size = 28;
$page_begin = ($page-1) * $page_size;
$db = "fotos.sqlite";
$db_table_name = 'casalusa';
$db_users_table_name = 'casalusa_users';
// $db_table_name = 'itatibafoo';
$handle = sqlite_open($db) or die("Could not open database".sqlite_error_string(sqlite_last_error($handle)));

$q = "SELECT * FROM $db_table_name WHERE featured IS NOT NULL ORDER BY created_time DESC LIMIT $page_begin, $page_size";
$query = sqlite_query($handle, $q);
$featured = sqlite_fetch_all($query, SQLITE_ASSOC);
?>
  <body>
    <!--[if lt IE 7]>
<p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
    <![endif]-->

    <header>
      <div class="bg">
        <div class="content">
          <h1 class="logo ir">Uma casa portuguesa com certeza</h1>
          <nav>
            <ul>
              <li><a href="#projeto"><span><b>sobre</b><br>o projeto</a></span></li>
              <li><a href="#casa"><span><b>sobre</b><br>a casa<br>portuguesa</span></a></li>
              <li><a href="#regulamento"><span><b>leia/baixe</b><br>o regulamento</span></a></li>
              <li><a href="#coletivos"><span><b>coletivos</b><br>convidados</span></a></li>
              <li><a href="#inauguracao"><span><b>inauguração</b><br>do projeto</span></a></li>
              <li class="external"><a href="mailto:contato@umacasaportuguesacomcerteza.com"><span><b>fale</b><br>conosco</span></a></li>
            </ul>
          </nav>
          <div id="instagram-icon"></div>
        <div>
      </div>
    </header>

    <div id="content">
      <!-- <div id="ref">
        <img src="img/ref-promo.png" />
      </div> -->
      <div id="main">
          <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST'){

/*------ Showcases --------------*/
  //clean old featured flags
  if (count($featured) > 0){
    $old_featured_ids = array();
    foreach ($featured as $old_featured) {
      $old_featured_ids[] = "'". $old_featured["id"] . "'";
    }
    $old_featured_list = implode(',', $old_featured_ids);
    $q = "UPDATE $db_table_name SET featured=NULL WHERE id IN($old_featured_list)";
    $ok = sqlite_exec($handle, $q, $error);
  }
  $q = "";
  foreach ($_POST['destaques'] as $key => $photo_id) {
    if ($photo_id == ''){
      continue;
    }
    $position = $key;
    $q .= "UPDATE $db_table_name
           SET featured='$position',
               featured_time=datetime('now')
          WHERE photo_id = '$photo_id';";
  }
  if ($q != ""){
    $ok = sqlite_exec($handle, $q, $error);
  }

  if (strlen($_POST["recent_photo_ids"]) > 2){

/*------ Blacklisted photos --------------*/
    //clean old blocked flags
    $recent_photo_ids = "'" . implode("','", explode(",", $_POST["recent_photo_ids"])) . "'";
    $q = "SELECT * FROM $db_table_name
          WHERE photo_id IN ($recent_photo_ids)
          AND blacklisted_photo IS NOT NULL";
    $query = sqlite_query($handle, $q);
    $blocked = sqlite_fetch_all($query, SQLITE_ASSOC);
    if (count($blocked) > 0){
      $old_blocked_ids = array();
      foreach ($blocked as $old_blocked) {
        $old_blocked_ids[] = "'". $old_blocked["id"] . "'";
      }
      $old_blocked_list = implode(',', $old_blocked_ids);
      $q = "UPDATE $db_table_name SET blacklisted_photo=NULL WHERE id IN($old_blocked_list)";
      $ok = sqlite_exec($handle, $q, $error);
    }
    //re-add the blocked flags
    $q = "";
    if($_POST['blacklisted-photos']){
      foreach ($_POST['blacklisted-photos'] as $photo_id => $status) {
        $q .= "UPDATE $db_table_name
               SET blacklisted_photo='1'
              WHERE photo_id = '$photo_id';";
      }
      if ($q != ""){
        $ok = sqlite_exec($handle, $q, $error);
      }
    }

/*------ Blacklisted users --------------*/
    //clean old blocked users
    $affected_users = "'" . implode("','", explode(",", $_POST["affected_users"])) . "'";
    $q = "SELECT * FROM $db_users_table_name
          WHERE user_id IN ($affected_users)
          AND blocked IS NOT NULL";
    $query = sqlite_query($handle, $q);
    $blocked = sqlite_fetch_all($query, SQLITE_ASSOC);
    if (count($blocked) > 0){
      $old_blocked_users = array();
      foreach ($blocked as $old_blocked) {
        $old_blocked_users[] = "'". $old_blocked["id"] . "'";
      }
      $old_blocked_list = implode(',', $old_blocked_users);
      $q = "UPDATE $db_users_table_name SET blocked=NULL WHERE id IN($old_blocked_list)";
      $ok = sqlite_exec($handle, $q, $error);
    }

    //re-add the blocked flags
    $q = "";
    if($_POST['blacklisted-users']){
      foreach ($_POST['blacklisted-users'] as $user_id => $status) {
        $q .= "UPDATE $db_users_table_name
               SET blocked='1'
              WHERE user_id = '$user_id';";
      }
      if ($q != ""){
        $ok = sqlite_exec($handle, $q, $error);
      }
    }
  }


}
//query for recent photos
$q = "SELECT * FROM $db_table_name ORDER BY created_time DESC LIMIT $page_begin, $page_size";
$query = sqlite_query($handle, $q);
$recent = sqlite_fetch_all($query, SQLITE_ASSOC);

//query for featured photos
$q = "SELECT * FROM $db_table_name WHERE featured IS NOT NULL ORDER BY featured";
$query = sqlite_query($handle, $q);
$featured = sqlite_fetch_all($query, SQLITE_ASSOC);

//query for popular photos
$q = "SELECT * FROM $db_table_name ORDER BY likes_count DESC LIMIT 0, 9";
$query = sqlite_query($handle, $q);
$most_popular = sqlite_fetch_all($query, SQLITE_ASSOC);
$most_popular = array_reverse($most_popular);

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

$home_showcase = array();

foreach ($featured as $featured_photo){
  $home_showcase[$featured_photo["featured"]-1] = $featured_photo;
}

for($i=0; $i<8; $i++){
  if(!$home_showcase[$i]){
    $home_showcase[$i] = array_pop($most_popular);
  }
}
          ?>
        <form action="" method="POST">
        <ol class="destaques">
          <?php
for($index=0; $index<8; $index++){
  $entry = $home_showcase[$index];
// foreach ($home_showcase as $index => $entry) {
  $id_to_display = '';
  if ($entry["featured"] > 0){
    $id_to_display = $entry["photo_id"];
  }
  echo '<li><a href="' . $entry["link"] . '"><img src="'. $entry["image_url"].
        '"></img></a><textarea name="destaques['.($index+1).']">'.$id_to_display.'</textarea><span>'. $entry["likes_count"] .'</span></li>';
}
          ?>
        </ol>
        <input type="hidden" id="recent_photo_ids" name="recent_photo_ids" />
        <input type="hidden" id="affected_users" name="affected_users" />
        <p class="primeiro"><a href="#" id="submit_link">Clique aqui para Atualizar</a></p>
        <p>Para definir um destaque, copie o ID de qualquer foto abaixo e cole num campo acima.</p>
        <p>Você pode <span class="amarelo">bloquear uma foto</span> ou <span class="vermelho">bloquear todas as fotos de um usuário</span>.</p>
        <p class="ultimo">Fotos com estrela já foram destaque algum dia.</p>
        <ol class="recentes">
          <?php
foreach ($recent as $entry) {
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
            <span>'.
            (!is_null($entry["featured_time"])?'<i class="destaque-icon"></i>':'').
              '<i class="blacklist-photo-icon"></i>
              <input type="checkbox" name="blacklisted-photos[' . $entry["photo_id"] . ']" '.
              (($entry["blacklisted_photo"] == '1')?'checked=checked':'') . '/>
              <i class="blacklist-user-icon"></i>
              <input class="block-user" type="checkbox" name="blacklisted-users[' . $entry["user_id"] . ']" '.
              ((in_array($entry["user_id"], $blacklisted_users))?'checked=checked':'') . '/>
            </span>
          </fieldset>
        </li>';
}
          ?>
        </ol>
        <div id="mouse-over">
        </div>
</form>
      </div> <!-- main -->
    </div> <!-- content -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.0.min.js"><\/script>')</script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js?v=1345764311310"></script>
    <script src="js/admin.js?v=1345764311310"></script>

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
    <script>
      var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
      (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
      g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
      s.parentNode.insertBefore(g,s)}(document,'script'));
    </script>
  </body>
</html>