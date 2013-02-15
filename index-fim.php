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
    <link rel="stylesheet" href="css/main.css?v=1358346194">
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
              <li><a href="#projeto"><span><b>sobre</b><br>o projeto</span></a></li>
              <li><a href="#casa"><span><b>sobre</b><br>a casa<br>portuguesa</span></a></li>
              <li><a href="#regulamento"><span><b>leia/baixe</b><br>o regulamento</span></a></li>
              <li><a href="#coletivos"><span><b>coletivos</b><br>convidados</span></a></li>
              <li><a href="#inauguracao"><span><b>inauguração</b><br>do projeto</span></a></li>
              <li class="external"><a href="http://vimeo.com/49256328" target="_blank"><span><b>veja</b><br>a vinheta</span></a></li>
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

//query for recent
if($_GET['showcase_only']=="yes"){
  $q = "SELECT * FROM $db_table_name WHERE
        blacklisted_photo IS NULL AND
        user_id NOT IN($blacklisted_users_list) AND
        featured_time IS NOT NULL";
}else{
  $q = "SELECT * FROM $db_table_name WHERE
        blacklisted_photo IS NULL AND
        user_id NOT IN($blacklisted_users_list)";
}
if(isset($_GET['order_by']) && $_GET['order_by']=="likes"){
  $q .= " ORDER BY likes_count DESC LIMIT $page_begin, $page_size";
}else{
  $q .= " ORDER BY created_time DESC LIMIT $page_begin, $page_size";
}
$query = sqlite_query($handle, $q);
$recent = sqlite_fetch_all($query, SQLITE_ASSOC);

//query for featured
$q = "SELECT * FROM $db_table_name WHERE
      featured IS NOT NULL
      ORDER BY featured";
$query = sqlite_query($handle, $q);
$featured = sqlite_fetch_all($query, SQLITE_ASSOC);

//query for most liked
$q = "SELECT * FROM $db_table_name WHERE
      blacklisted_photo IS NULL AND
      user_id NOT IN($blacklisted_users_list)
      ORDER BY likes_count DESC LIMIT 0, 9";
$query = sqlite_query($handle, $q);
$most_popular = sqlite_fetch_all($query, SQLITE_ASSOC);
$most_popular = array_reverse($most_popular);

//home_showcase is featured combined with most liked (10 photos)
$home_showcase = array();
foreach ($featured as $featured_photo){
  $home_showcase[$featured_photo["featured"]-1] = $featured_photo;
}
for($i=0; $i<8; $i++){
  if(!isset($home_showcase[$i])){
    $home_showcase[$i] = array_pop($most_popular);
  }
}
          ?>
        <ol class="destaques">
          <?php
for($index=0; $index<8; $index++){
  $entry = $home_showcase[$index];
  $id_to_display = '';
  if ($entry["featured"] > 0){
    $id_to_display = $entry["photo_id"];
  }
  $class = ($index == 0) ? 'class="first"':'';
  echo '<li '. $class .'><a target="_blank" href="' . $entry["link"] . '"><img src="'. $entry["image_url"].'"></img><span class="likes_count">'. $entry["likes_count"] .'</span></a></li>';
}
          ?>
        </ol>
      <style>
#content #popup-opener, #content #message-popup {
  background: none repeat scroll 0 0 rgba(238, 25, 65, 0.9);
}
#content #message-popup h2 {
  color: #ffc8e1;
  margin-top: 18px;
  margin-bottom: 10px;
}
#content #message-popup .tag {
  color: #fff;
}
#main p.primeiro{
  font-size: 23px;
  padding-bottom: 27px;
  margin-left: 0px !important;
}
      </style>
        <p class="primeiro">O Estúdio Madalena e a FUNARTE agradecem a todos os fotógrafos que participaram do projeto.</p>
        <ol class="recentes">
          <?php
foreach ($recent as $entry) {
  echo '<li><a target="_blank" href="' . $entry["link"] . '"><img src="'
                       . $entry["image_url"] . '"></img></a></li>';
}
          ?>
        </ol>
        <div id="mouse-over">
        </div>
<!--
 -->
      </div> <!-- main -->
        <aside id="popup-opener" class="closed">
          <p>
            <a href="#">Qual é a sua imagem da casa portuguesa?<i class="caret"></i></a>
          </p>
        </aside>
        <aside id="message-popup" class="closed">
          <h1>Uma Casa portuguesa com Certeza encerra-se com +de 6 mil imagens</h1>
          <h2>
            marcadas com as hashtags
            <span class="tag">#casalusa</span> e
            <span class="tag">#umacasaportuguesacomcerteza</span>.
            Os vencedores do concurso e os participantes das exposições em
            Lisboa e no Rio de Janeiro serão anunciados no dia 10 de março.
          </h2>
          <p class="close-button"><a href="#">fechar</a></p>
        </aside>
        <aside id="share">
          <ul>
            <li class="facebook"><a href="https://www.facebook.com/umacasaportuguesacomcerteza" target="_blank">facebook</a></li>
            <li class="vimeo"><a href="http://vimeo.com/casaportuguesacomcerteza" target="_blank">vimeo</a></li>
            <li class="twitter"><a href="https://twitter.com/umacasalusa" target="_blank">twitter</a></li>
          </ul>
        </aside>
        <aside id="projeto">
          <p>
            O projeto Uma Casa portuguesa com Certeza foi concebido especialmente para o Ano do Brasil em Portugal e será uma homenagem de brasileiros e portugueses a Portugal! A proposta é produzir correspondências visuais entre fotógrafos brasileiros e portugueses com o tema da 'casa portuguesa'. O projeto soma um Concurso fotográfico e exposições.
          </p>
          <p>
            A participação está aberta a todos os fotógrafos amadores e profissionais que queiram participar, basta ser usuário - ou passar a ser - do Instagram, seguir as regras do jogo e participar do processo de seleção. Serão aceitos participantes residentes no Brasil e em Portugal. Leia ou <a href="./downloads/Regulamento_Uma_Casa_Portuguesa_com_Certeza.pdf">baixe o regulamento</a> para saber mais.
          </p>
          <p class="menor">
          Curadoria: Iatã Cannabrava e Milton Guran<br>
          Produção executiva: Helena Ruschel<br>
          Logotipo: Elohim Barros<br>
          Site: Helena Ruschel, Mauricio e Fabricio Zuardi<br>
          Vinheta: Doca e Vana
          </p>
        </aside>
        <aside id="casa">
          <p>
            A dita casa portuguesa, naturalmente, vem se reinventando continuamente, seja por estar no Brasil seja por estar em Portugal, mas guarda uma certa matriz que, temos certeza, servirá como o condutor da identidade portuguesa. A casa portuguesa, tomada como tema, pode ser vista de dentro, de fora, e também situada no seu entorno. O que buscamos é estimular uma percepção e/ou uma reflexão sobre o que seria esta casa no imaginário de portugueses e de brasileiros.
          </p>
        </aside>
        <aside id="regulamento">
          <section>
            <h1><a href="./downloads/Regulamento_Uma_Casa_Portuguesa_com_Certeza_prorrogado.pdf">Baixe o Regulamento</a></h1>
            <p>
              <a href="./downloads/Regulamento_Uma_Casa_Portuguesa_com_Certeza_prorrogado.pdf">Clique aqui</a> e baixe o regulamento para guardar, imprimir ou ler mais tarde.
            </p>
              <h1>REGULAMENTO</h1>
              <p>Tomando por tema um dos aspectos da cultura portuguesa mais marcados no imaginário brasileiro, imortalizado pelo cancioneiro popular: a casa portuguesa, a proposta deste projeto é produzir uma correspondência visual entre fotógrafos brasileiros e portugueses.</p>
              <p>A dita casa portuguesa, naturalmente, vem se reinventando continuamente, seja por estar no Brasil seja por estar em Portugal, mas guarda uma certa matriz que, temos certeza, servirá como o condutor da identidade portuguesa. A casa portuguesa, tomada como tema, pode ser vista de dentro, de fora, e também situada no seu entorno. O que buscamos é estimular uma percepção e/ou uma reflexão sobre o que seria esta casa no imaginário de portugueses e de brasileiros.</p>
              <h1>1. PROJETO</h1>
              <p>1.1 O presente concurso tem por objetivo convidar fotógrafos amadores e profissionais a produzirem imagens que retratem a casa portuguesa, no Brasil e em Portugal no espírito descrito no parágrafo inicial, através de “smartphones” de qualquer fabricante, utilizando o aplicativo Instagram. Os uploads com a tag #umacasaportuguesacomcerteza ou #casalusa de fotos no Instagram serão apresentadas diariamente, de 20 de setembro de 2012 a 15 de fevereiro de 2013, no site do projeto www.umacasaportuguesacomcerteza.com;</p>
              <p>1.2 Os participantes devem ser residentes no Brasil ou em Portugal;</p>
              <p>1.3 Para efeito da entrega do prêmio, estão habilitados a participar deste concurso maiores de 18 anos ou menores com autorização expressa dos responsáveis;</p>
              <p>1.4 O participante deverá ser usuário - ou passar a ser - do Instagram [www.instagram.com] e do Facebook [www.facebook.com];</p>
              <p>1.5 Os interessados em participar deverão subir no Instagram as imagens com a hashtag #umacasaportuguesacomcerteza. A organização poderá mostrar as mesmas fotografias na funpage do Facebook do projeto;</p>
              <p>1.6 A primeira ʻpostagemʼ acontecerá na inauguração do projeto, por ocasião da 22a edição dos Encontros da Imagem de Braga, no dia 20 de setembro de 2012;</p>
              <p>1.7 As imagens enviadas serão mediadas para certificação do conteúdo, podendo ser bloqueados usuários que não respeitarem as regras descritas no regulamento;</p>
              <p>1.8 Os curadores do projeto semanalmente selecionam as imagens que irão participar da ação aparecendo no site do evento. Terão destaque as fotografias com mais ʻLikesʼ ou algumas destacadas pela organização;</p>
              <p>1.9 Serão convidados três coletivos fotográficos para realizar ensaios sobre o tema e estimular a participação do público;</p>
              <p>1.10 Haverá duas formas de avaliação: júri popular e júri de experts:</p>
              <p>Júri popular: As fotos serão avaliadas pelo público, e o fotógrafo brasileiro e o fotógrafo português com mais “Likes” no Instagram serão premiados.</p>
              <p>Júri de experts: Da mesma forma, um brasileiro e um português serão selecionados pela banca de jurados, formada por Milton Guran [FotoRio], Iatã Cannabrava [Paraty em Foco] e Ângela Ferreira e Rui Prata [Encontros da Imagem de Braga] e coordenado pelo Comissário geral do Ano do Brasil em Portugal;</p>
              <p>1.11 Serão atribuídos 4 [quatro] prêmios, que consistem em uma viagem com estada para o país de não residência, 2 [dois] para brasileiros e 2 [dois] para portugueses, sendo que 2 [dois] ganhadores serão selecionados por júri popular e 2 [dois] por um júri de experts;</p>
              <p>1.12 Os curadores irão selecionar as 50 fotografias que irão participar das exposições Uma Casa portuguesa com Certeza, intinerantes no Brasil e em Portugal. Esta seleção vai observar os votos populares, mas não está obrigada a, necessariamente, incluir as imagens ganhadoras do voto popular na seleção final a ser exposta;</p>
              <p>1.13 A exposição acontecerá no Brasil e, simultaneamente, em Portugal e terá uma intinerância em cada país;</p>
              <p>1.14 A organização reserva o direito de alterar estas regras a qualquer momento se isso for inevitável, devido à força maior;</p>
              <p>1.15 Cronograma: envio de imagens de 20 de setembro a 15 de fevereiro e divulgação do resultado dia 15 de março.</p>
              <h1>2. REGRAS DO JOGO DA AÇÃO</h1>
              <p>2.1 Para participar, o candidato deverá obrigatoriamente ter contas no Instagram [www.instagram.com] e no Facebook [www.facebook.com];</p>
              <p>2.2 Para participar, o candidato deverá curtir a funpage do Facebook do projeto http://www.facebook.com/casaportuguesacomcerteza;</p>
              <p>2.3 Não serão aceitas imagens de cunho pornográfico, racista ou discriminatório de qualquer espécie;</p>
              <p>2.4 O proponente deverá formatar sua fotografia obedecendo às exigências do regulamento.</p>
              <h1>3. ENVIO DURANTE A AÇÃO</h1>
              <p>3.1 As imagens deverão ser ʻpostadasʼ no Instagram com a hashtag #umacasaportuguesacomcerteza ou #casalusa de 20 de setembro até 15 de fevereiro de 2013, às 23h59 do horário de Brasília;</p>
              <p>3.2 Será permitido que o participante adicione outros hashtags além de #umacasaportuguesacomcerteza ou #casalusa para eventual busca.</p>
              <h1>4. DA SELEÇÃO PARA PRÊMIO</h1>
              <p>4.1 As fotografias recebidas pelo projeto Uma Casa portuguesa com Certeza passarão por duas etapas de avaliação: Habilitação e Análise;</p>
              <p>4.1.1 Habilitação</p>
              <p>A etapa de habilitação, com caráter eliminatório, consistirá na verificação do cumprimento de todas as exigências constantes no regulamento;</p>
              <p>4.1.2 Análise</p>
              <p>Juri popular: análise do público através de ʻLIkesʼ no Instagram</p>
              <p>Juri de experts: análise pela comissão de avaliação formada por Milton Guran [FotoRio], Iatã Cannabrava [Paraty em Foco] e Ângela Ferreira e Rui Prata [Encontros da Imagem de Braga] e coordenado pelo Comissário geral do Ano do Brasil em Portugal. Neste caso, a análise das imagens se dará observando a qualidade e sensibilidade artística do participante e pertinência em relação ao tema.</p>
 
              <h1>5. DA DIVULGAÇÃO DO RESULTADO</h1>
              <p>5.1 Os nomes dos premiados serão divulgados no dia 15 de março de 2013, no site www.umacasaportuguesacomcerteza.com;</p>
              <p>5.2 Os proponentes cujas fotografias forem selecionadas para receber prêmio ou para as exposições receberão comunicados, via e-mail, com instruções para formalização da concordância quanto às condições do procedimento.</p>
              <h1>6. DOS PRÊMIOS</h1>
              <p>6.1 Serão um total de 4 [quatro] prêmios, 2 [dois] com júri popular e 2 [dois] com júri de experts, 2 [dois] para residentes no Brasil e 2 [dois] para residentes em Portugal;</p>
              <p>6.2 O prêmio consiste em passagem e 3 [três] dias de estádia em Hotel 3 [três] estrelas em país de não residência do ganhador para abertura das exposições;</p>
              <p>6.3 Os prêmios não são transferíveis.</p>
              <h1>7. DAS EXPOSIÇÕES</h1>
              <p>7.1 A primeira exposição no Brasil acontece simultânea a exposição em Portugal;</p>
              <p>7.2 Os participantes selecionados para exposição serão contatados pela produção. Caso necessário, será solicitado o envio de imagem original, em até cinco dias úteis. Caso a imagem não seja enviada ou o selecionado não seja encontrado, ele será substituído;</p>
              <p>7.3 A produção do projeto é responsável pela impressão, transporte, montagem, desmontagem, sinalização e divulgação das exposições;</p>
              <p>7.4 A organização não se responsabiliza por candidatos que não possuem plena autoria das obras a serem expostas. De forma que é dever do participante ter a autorização de terceiros que, por ventura, tenham direitos conexos sobre a produção apresentada, se responsabilizando, inclusive, por eventuais reivindicações de terceiros quanto ao uso não autorizado;</p>
              <p>7.5 Em fotos selecionadas que identificam indivíduos, o participante deve ter o consentimento específico de tais pessoas para expor a fotografia em questão. Em caso de menores, o participante deve ter o consentimento expresso dos pais ou responsáveis. Os organizadores do concurso não assumirão responsabilidades para o fracasso do participante desta obrigação;</p>
              <p>7.6 Pela adesão ao presente concurso, o candidato inscrito, que venha a ser selecionado para exposição, autoriza a organização do evento a utilizar suas imagens em mídia impressa ou eletrônica, exclusivamente para divulgação do projeto;</p>
              <p>7.7 As imagens selecionadas para exposição não serão objeto de utilização comercial de qualquer tipo.</p>
              <h1>8. DAS DISPOSIÇÕES GERAIS</h1>
              <p>8.1 A visitação das exposições do projeto Uma Casa portuguesa com Certeza será gratuita;</p>
              <p>8.2 As dúvidas poderão ser encaminhadas exclusivamente para o e-mail contato@umacasaportuguesacomcerteza.com;</p>
              <p>8.3 O ato da inscrição implica em plena concordância com os termos aqui estabelecidos; 8.4 Os casos omissos serão avaliados pela curadoria do projeto.</p>
          </section>
        </aside>
        <aside id="coletivos" class="vcard">
          <h3>Foram convidados três Coletivos fotográficos para participar do projeto, durante o período do Concurso, eles também estarão fotografando com Instagram e estimulando o nosso olhar.</h3>
          <section>
            <img class="foto" src="img/coletivo_kameraphoto.jpg" />
            <div class="content">
              <h1>Kameraphoto</h1>
              <h2>Portugal</h2>
              <p>
                A [kameraphoto] é um colectivo de fotógrafos fundado em Janeiro de 2003. É uma estrutura agregadora de diferentes olhares da fotografia contemporânea portuguesa, comprometida com a criação e realização de projectos colectivos. O colectivo incentiva, igualmente, os seus membros a realizar projectos pessoais. O empenho dedicado a produções colectivas bem como a individuais torna a [kameraphoto] num espaço crítico de promoção da fotografia que permite à estrutura ser eclética nas suas visões e percepções do mundo, bem como nas diversas formas expressas através do suporte fotográfico.
              </p>
              <p>Fazem parte do projeto: Jordi Burch, Alexandre de Almeida e Guillaume Pazat.</p>
            </div>
          </section>
          <section>
            <img class="foto" src="img/coletivo_pangeia_de_dois.jpg" />
            <div class="content">
              <h1>Pangéia de dois</h1>
              <h2 class="adr"><span class="region">SP</span> - <span class="country">Brasil</span></h1>
              <p>
                Pangéia de dois é um coletivo multimídia baseado em São Paulo, formado por Malu Teodoro e Vinicius Assencio. Nasceu em 2009 com a criação do blog <a href="http://pangeiadedois.wordpress.com">pangeiadedois.wordpress.com</a>, durante o tempo em que a dupla viveu no México. Hoje, em São Paulo, desenvolve pesquisa teórica e experimental sobre imagens contemporâneas, relações pessoais e sentimentos afetivos.
              </p>
            </div>
          </section>
          <section>
            <img class="foto" src="img/coletivo_o_estendal.jpg" />
            <div class="content">
              <h1>O Estendal</h1>
              <h2>RJ - Brasil</h2>
              <p>
                O Estendal é um coletivo carioca formado por artistas e fotógrafos que tem em comum o desejo de levar fotografia/ arte para as ruas. Nesta época de massificação da imagem a intenção é democratizar a fotografia propondo ações que dialoguem com a população em geral. Desenvolvendo assim o interesse pela arte.
              </p>
              <p>
                O coletivo vem trabalhando desde 2010, e já promoveu ações em Lisboa, Rio de Janeiro, Sã o Paulo, Curitiba, Tiradentes, São João Del Rey e Juiz de Fora.
              </p>
            </div>
          </section>
        </aside>
        <aside id="inauguracao">
          <p>O projeto estará presente, entre os dias 26 e 30 de setembro, no Festival Internacional - Partay em Foco  [<a href="http://www.paratyemfoco.com" target="_blank">www.paratyemfoco.com</a>] em duas ações, ambas contidas em grandes conteiners estacionados ao lado da Praça Matriz, em Paraty.</p>
          <p>O primeiro conteiner vai apresentar o trabalho de Ana Lúcia Mariz _ O que Paulo Reis me ensinou, uma exposição que faz referência a casa lusa</p>
          <p>O segundo conteineir abriga uma estação de trabalho e impressão fotográfica. Durante todo o Festival a estação estará em atividade, imprimindo e montado uma exposição dentro do conteiner, com os Instagrams enviados para projeto Uma Casa portuguesa com Certeza através do #casalusa ou #umacasaportuguesacomcerteza.</p>
          <p>A inauguração do projeto acontecerá por ocasião da 22ª edição dos Encontros da Imagem de Braga [<a href="http://www.encontrosdaimagem.com" target="_blank">www.encontrosdaimagem.com</a>], no dia 20 de setembro de 2012, as 11 horas no Espaço Emergentes DST, Rua do Raio 175, Braga. As primeiras ‘postagens’ serão feitas pelo presidente da Funarte, Antonio Grassi. Também estarão presentes na inauguração, Milton Guran, curador do projeto e Rui Prata e Ângela Ferreira responsáveis pelo Encontros da Imagem de Braga.</p>
          <a href="http://www.encontrosdaimagem.com" target="_blank"><img class="logo-esq" src="img/logo_encontros-da-imagem.png" /></a>
          <a href="http://www.paratyemfoco.com" target="_blank"><img class="logo-dir" src="img/logo_paraty-em-foco.png" /></a>
        </aside>

    </div> <!-- content -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.0.min.js"><\/script>')</script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js?v=1358346194"></script>

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
    <script>
      var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
      (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
      g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
      s.parentNode.insertBefore(g,s)}(document,'script'));
    </script>
  </body>
</html>