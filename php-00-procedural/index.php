<?php
//Importando o autoload e o TwitterOAuth
require "vendor/autoload.php"
use Abraham\TwitterOAuth\TwitterOAuth;

//CHAVES DE ACESSO - Altere as chaves para as suas, em https://developer.twitter.com/en/apps/
$token_de_acesso = "{ SEU TOKEN DE ACESSO }";
$token_secreto_de_acesso = "{ SEU TOKEN DE ACESSO SECRETO }";

//INICIALIZAÇÃO DO TWITTEROAUTH
$connection = new TwitterOAuth("{ SUA CHAVE DE CONSUMIDOR }", "{ SUA CHAVE DE CONSUMIDOR SECRETA }", $token_de_acesso, $token_secreto_de_acesso);

//VERIFICA AS CREDENCIAIS
$content = $connection->get("account/verify_credentials");

//PEGA OS ULTIMOS 25 TWEETS DA SUA TIMELINE
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

//VERIFICANDO SE ESTÁ VINDO ALGUM DADO DO FORMULÁRIO
if(isset($_POST['btn-tt'])){
  //PEGA O TWEET
  $tweet = $_POST['tweet'];
  
  //POSTA O TWEET NA SUA CONTA
  $status = $connection->post("statuses/update", ["status" => $tweet]);
  
  //ATUALIZA O SEU FEED PEGANDO NOVAMENTE OS DADOS DA SUA TIMELINE
  $tweets = $connection->get("statuses/home_timeline", ["count" => 25, "exclude_replies" => true]);
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  
  <title>Meu Twitter</title>

  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  
</head>
<body class="bg-light">
  <!--   H E A D E R   -->
  <nav class="nav navbar bg-primary border-bottom p-1">
    <div class="container-fluid text-center justify-content-center">
      <a href="" class="navbar-brand">
       
      </a>
      <span class="h3 text-light">Meu site do Twitter!!</span>
    </div>
  </nav>
  <!-- /  H E A D E R   -->

  <!-- P O S T   -->
  <div class="container border shadow rounded mt-1 mb-1 shadow p-5 ">
    <form action="index.php" method="POST">
      <div class="row">
        <div class="col-10">
          <center>
          <input type="text" name="tweet" placeholder="O que estou pensando?..." class="form-control border border-primary p-4"/>
          </center>
        </div>
        <div class="col-2 justify-content-end ">
          <input name="btn-tt" type="submit" class="btn btn-lg btn-primary  rounded border text-light p-2 justify-content-end" value="Twittar!">
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
  <section class="container border-primary  bg-light  shadow p-2 rounded mb-3 ">
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
            //Se existir trends
            if(isset($trends)){
            //Se tiver algum trend 
              if(count($trends)>0){
                //Pega o local se existir, caso contrário recebe falso  
                $local = (isset($trends[0]['locations'][0]['name'])) ? $trends[0]['locations'][0]['name'] : false;
                //Caso haja um local
                if ($local) {
                  //Pega o trend do local  
                  $trend_local = $trends[0]["trends"][0]["name"];
                  //Localização
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
      <?php     
                }
              }
            } 
        endforeach 
      ?>
      </div>
      <!-- /T R E N D S  -->
    </div>
    <!-- / C O N T E U D O S   -->
  </section>
  <!--  /T O D O   -->

  <?php  }else{   ?>
     <div class="alert alert-primary alert-dismissible fade show" role="alert">
      Ultima requisição não deu certo!
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
 
<?php }  ?>
</body>
</html>