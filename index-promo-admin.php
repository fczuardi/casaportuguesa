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
$page = 1;
$page_size = 28;
$page_begin = ($page-1) * $page_size;
$db = "fotos.sqlite";
$db_table_name = 'casalusa';
// $db_table_name = 'itatibafoo';
$handle = sqlite_open($db) or die("Could not open database".sqlite_error_string(sqlite_last_error($handle)));
$q = "SELECT * FROM $db_table_name ORDER BY created_time DESC LIMIT $page_begin, $page_size";
$query = sqlite_query($handle, $q);
$recent = sqlite_fetch_all($query, SQLITE_ASSOC);

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
  if (count($featured) > 0){
    //clean old featured flags
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
  $ok = sqlite_exec($handle, $q, $error);


}
$q = "SELECT * FROM $db_table_name WHERE featured IS NOT NULL ORDER BY featured";
$query = sqlite_query($handle, $q);
$featured = sqlite_fetch_all($query, SQLITE_ASSOC);


$q = "SELECT * FROM $db_table_name ORDER BY likes_count DESC LIMIT 0, 9";
$query = sqlite_query($handle, $q);
$most_popular = sqlite_fetch_all($query, SQLITE_ASSOC);
$most_popular = array_reverse($most_popular);

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
        '"></img></a><textarea name="destaques['.($index+1).']">'.$id_to_display.'</textarea>/></li>';
}
          ?>
        </ol>
        <p class="primeiro"><a href="#" id="submit_link">Clique aqui para Atualizar</a></p>
        <p>Para definir um destaque, copie o ID de qualquer foto abaixo e cole num campo acima.</p>
        <p>Você pode <span class="amarelo">bloquear uma foto</span> ou <span class="vermelho">bloquear todas as fotos de um usuário</span>.</p>
        <p class="ultimo">Fotos com estrela já foram destaque algum dia.</p>
        <ol class="recentes">
          <?php
foreach ($recent as $entry) {
  echo '<li data-username="'.$entry["username"].'" data-photo_id="'.$entry["photo_id"].'" data-featured="'.$entry["featured"].'">
          <a href="' . $entry["link"] . '"><img src="'
                     . $entry["image_url"] . '"></img></a>
          <fieldset>
            <p class="username-label">' . $entry["username"] . '</p>
            <label class="photo-id"><textarea>' . $entry["photo_id"] . '</textarea>
            <span>
              <i class="destaque-icon"></i>
              <i class="blacklist-photo-icon"></i>
              <input type="checkbox" name="blacklisted-photos[' . $entry["photo_id"] . ']" />
              <i class="blacklist-user-icon"></i>
              <input type="checkbox" name="blacklisted-users[' . $entry["username"] . ']" />
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