<?php
//Chama o au
require "vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;
//CHAVES DE ACESSO - altere as chaves para as suas, pois quando você as utilizar já estão inativas
$token_de_acesso = "3004691099-7dMUh3whTvUsduD8bXhoRI7deiVwVgWVLH1HzK7";
$token_secreto_de_acesso = "G7cqr53IP3gWIL7sCBVBa4NfanFCOAHiB8Wwl4FUdltIu";
//INICIALIZAÇÃO DO TWITTEROAUTH
$connection = new TwitterOAuth("2paST3lJEjYxUvIg6sPPfWgjJ", "7PmjFYBzHfOGzIBtJeQmAO67i46YeZf3hMPdlR90po1NeVyIZ0", $token_de_acesso, $token_secreto_de_acesso);
//VERIFICA AS CREDENCIAIS
$content = $connection->get("account/verify_credentials");
//PEGA OS ULTIMOS 25 TWEETS
$tweets = $connection->get("statuses/home_timeline", ["count" => 25, "exclude_replies" => true]);
//CÓDIGOS WOEID DISPONÍVEIS DA REGIÃO DO BRASIL
$locais =  [
  23424768,
  455819,
  455820,
  455821,
  455822,
  455823,
  455824,
  455825,
  455826,
  455827,
  455828,
  455830,
  455831,
  455833,
  455834,
  455867
];
//USANDO MÉTODO PRECÁRIO PARA VERIFICAR SE ESTAMOS RECEBENDO UMA REQUEST DO FORMULÁRIO DE INSERÇÃO DO TWEET
    if(isset($_POST['btn-tt'])){
      //PEGA O TWEET
      $tweet = $_POST['tweet'];
      //POSTA O TWEET NA SUA CONTA
      $status = $connection->post("statuses/update", ["status" => $tweet]);
      //ATUALIZA O SEU FEED PEGANDO NOVAMENTE OS DADOS DA SUA TIMELINE
      $tweets = $connection->get("statuses/home_timeline", ["count" => 25, "exclude_replies" => true]);
     }
      if(isset($_POST['btn-tt-loop'])){
      //PEGA O TWEET
      $tweet = $_POST['tweet-loop'];
      $qnt = intval($_POST['qnt-loop']);
      $i = 0;
      for ($i=0; $i < $qnt; $i++) { 
         $tweet = $_POST['tweet-loop']." (".$i.")";
        //POSTA O TWEET NA SUA CONTA
        $status = $connection->post("statuses/update", ["status" => $tweet]);
      }

      //POSTA O TWEET NA SUA CONTA
      $status = $connection->post("statuses/update", ["status" => $tweet]);
      //ATUALIZA O SEU FEED PEGANDO NOVAMENTE OS DADOS DA SUA TIMELINE
      $tweets = $connection->get("statuses/home_timeline", ["count" => 25, "exclude_replies" => true]);
     }

  ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  
  <title>Meu Twitter</title>

  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  
</head>
<body class="bg-light">
  <!--   H E A D E R   -->
  <nav class="nav navbar bg-primary border-bottom p-1">
    <div class="container-fluid text-center justify-content-center">
      <a href="" class="navbar-brand">
       
      </a>
      <span class="h3 text-light">Teste de Api do Twitter!</span>
    </div>
  </nav>
  <!-- /  H E A D E R   -->
  <!-- P O S T   -->
  <div class="container border shadow rounded mt-1 mb-1 shadow p-5 ">
    <form action="index.php" method="POST">
      <div class="row">
        <div class="col-10">
          <center>
          <input type="text" name="tweet" placeholder="O que você está pensando?..." class="form-control border border-primary p-4"/>
          </center>
        </div>
        <div class="col-2 justify-content-end ">
          <input name="btn-tt" type="submit" class="btn btn-lg btn-primary  rounded border text-light p-2 justify-content-end" value="Twittar!">
        </div>
      </div>
    </form>
    <!--  -->
    <form action="index.php" method="POST">
      <div class="row">
        <div class="col-7">
          <center>
          <input type="text" name="tweet-loop" placeholder="E ai?..." class="form-control border border-primary p-4"/>
          </center>
        </div>
        <div class="col-3">
          <center>
          <input type="number" name="qnt-loop" placeholder="n" class="form-control border border-primary p-4"/>
          </center>
        </div>
        <div class="col-2 justify-content-end ">
          <input name="btn-tt-loop" type="submit" class="btn btn-lg btn-primary  rounded border text-light p-2 justify-content-end" value="Loop!">
        </div>
      </div>
    </form>
  </div>
  <!-- /P O S T   -->

  <?php  //SE A ULTIMA REQUISIÇÃO HTTP DEU CERTO: ?>
  <?php  if($connection->getLastHttpCode() == 200){ ?>
  <!-- N A V -->
  <div class="container text-center mt-1  justify-content-center">
     <!-- N A V    I T E N S -->
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">
        Tweets
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="pills-trends-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">
        Trends
        </a>
      </li>
      
    </ul>
    <!-- /N A V    I T E N S -->
  </div>
  <!-- N A V -->
  <!--  T O D O   -->
  <div class="container border-primary  bg-light  shadow p-2 rounded mb-3 ">
    <!--  C O N T E U D O S   -->
    <div class="tab-content" id="pills-tabContent">
      <!--  T I M E    L I N E   -->
      <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
        <?php //LISTA OS TWEETS ?>
        <?php foreach($tweets as $data): ?>
      
          <div class="container border border-primary shadow bg-white rounded mb-2 p-2">
            <div class="row ">
              <div class="col-12">
                <?php echo "O usuário <b>".$data->user->name."</b> tweetou:";?>
              </div>
              <div class="col-12 p-1">
                <div class="container">
                  <?php echo $data->text; ?>
                </div>
              </div>
            </div>
          </div>

        <?php endforeach?> 
    
      </div>
       <!-- / T I M E    L I N E   -->
      <!-- T R E N D S  -->
      <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-trends-tab">
        <?php 
              //LISTA OS TRENDS QUE CADA REGIÃO ESPECIFICADA PELO CÓDIGO WOEID
              foreach($locais as $data):
                //transformando std em array
                $obj_trends = (array)$connection->get("trends/place",["id" => $data]);
                $trends = json_decode(json_encode($obj_trends), True);
                  
                  if(isset($trends)){
                    if(count($trends)>0){

                  $local = $trends[0]['locations'][0]['name'];
                  $trend_local = $trends[0]["trends"][0]["name"];
                  $url_local = $trends[0]["trends"][0]["url"];

        ?>
               <div class="container border border-primary shadow bg-white rounded mb-2 p-2">
                <div class="row ">
                  <div class="col-12">
                    <?php echo "Em <b>".$local."</b> a coisa mais falada é:";?>
                  </div>
                  <div class="col-12 p-1">
                    <div class="container">
                      <?php echo '<a class="text-dark" href="'.$url_local.'" target="_blank">'.$trend_local."</a>"; ?>
                    </div>
                  </div>
                </div>
              </div>
        <?php   }} endforeach ?>
      </div>
      <!-- /T R E N D S  -->
    </div>
    <!-- / C O N T E U D O S   -->
  <?php  }else{   ?>
     <div class="alert alert-primary alert-dismissible fade show" role="alert">
      Ultima requisição não deu certo!
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php   }  ?>
  <!--  /T O D O   -->
  </div>
</body>
</html>