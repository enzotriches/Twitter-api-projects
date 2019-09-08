<?php
//Chama o au
require "vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;
//CHAVES DE ACESSO
$token_de_acesso = "TOKEN_DE_ACESSO";
$token_secreto_de_acesso = "TOKEN_SECRETO_DE_ACESSO";
//INICIALIZAÇÃO DO TWITTEROAUTH
$connection = new TwitterOAuth("CHAVE_PUBLICA_DE_CONSUMIDOR", "CHAVE_PRIVADA_DE_CONSUMIDOR", $token_de_acesso, $token_secreto_de_acesso);
//VERIFICA AS CREDENCIAIS
$content = $connection->get("account/verify_credentials");
//PEGA OS ULTIMOS 25 TWEETS
$tweets = $connection->get("statuses/home_timeline", ["count" => 25, "exclude_replies" => true]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  
  <title>Meu Twitter</title>

  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous">
  </script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  
</head>
<body class="bg-light">
  <!--   Nav -->
  <nav class="nav navbar bg-primary border-bottom p-1">
    <div class="container-fluid text-center justify-content-center">
      <a href="" class="navbar-brand">
       
      </a>
      <span class="h3 text-light">Teste de Api do Twitter!</span>
    </div>
  </nav>
  <?php
     if(isset($_POST['btn-tt'])){
       $tweet = $_POST['tweet'];
       $status = $connection->post("statuses/update", ["status" => $tweet]);
       
        $tweets = $connection->get("statuses/home_timeline", ["count" => 25, "exclude_replies" => true]);
     }
  ?>
  <div class="container border shadow mt-1 mb-1 shadow p-1 ">
    <form action="index.php" method="POST">
      <div class="row">
        <div class="col-12">
          <input type="text" name="tweet" placeholder="O que você está pensando?..." class="form-control border border-primary p-3"/>
        </div>
        <div class="col-12 justify-content-end  p-1">
          <input name="btn-tt" type="submit" class=" ml-2 btn btn-lg btn-primary btn-rounded rounded border text-light p-1 h2" value="Twittar!">
        </div>
      </div>
    </form>
  </div>
  <!--  /Nav -->
  <!--  Errors  -->
  <?php  if($connection->getLastHttpCode() == 200){ ?>
  <div class="container text-center mt-1  justify-content-center">
   
    <div class="alert alert-success alert-dismissible fade show"   role="alert">
      Ultima requisição deu certo!
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
 
   
   </div>
  <!--  Tweets  -->
  <div class="container border-primary  bg-light  shadow p-2 rounded mb-3 ">
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
  <?php  }else{   ?>
     <div class="alert alert-primary alert-dismissible fade show" role="alert">
      Ultima requisição não deu certo!
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php   }  ?>
  <!--  /Errors  -->
  </div>
  
</body>
</html>