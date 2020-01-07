<?php
//Chama o autoload
require "vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;
//CHAVES DE ACESSO - altere as chaves para as suas, pois quando você as utilizar já estão inativas
$token_de_acesso = "3004691099-kuYjdyqJvhfJJV4Q5l2dW4kae83Hm3Sqn0JLfxe";
$token_secreto_de_acesso = "xeGWOAVGE9DdcfG9o8XuMIfYoaTxxIWSbpxhnH3CzMK5g";
//INICIALIZAÇÃO DO TWITTEROAUTH
$connection = new TwitterOAuth("1Mmi1OuFHR62qAJjvu0VBVyvv", "TxYsdGBeuK8vHaALNQ90E56uSo99WAycGu42IDu4ZxWQ96GTaO", $token_de_acesso, $token_secreto_de_acesso);
//VERIFICA AS CREDENCIAIS
$content = $connection->get("account/verify_credentials");
//PEGA OS ULTIMOS 25 TWEETS
$tweets = $connection->get("statuses/user_timeline", ["count" => 130, "exclude_replies" => false, "screen_name" => "itsabadwolf"]);


          //Juntar todo o texto em uma variavel
          $todo_texto = '';
          foreach ($tweets as $value) {
            $todo_texto .= ' '.$value->text.' ';
          }


          //Separar as palavras dessa variavel com um split a cada '' 
          $todas_palavras = explode(' ', $todo_texto);
          $i=0;
            //Deixar cada palavra em minúsculo
            foreach ($todas_palavras as $value) {
              $simbolos = array(',','.','/','%','&','*','$',':',';','?');
              foreach ($simbolos as $sm) {
                $valfenda = str_replace($sm, ',', $value);
                $todas_palavras[$i] = strtolower(trim($valfenda));
              }
             $i++;
            }
          $i=0;  

          //Verificar conectivos e palavras comuns
          $palavras_comuns = array('a','e','o','para','nos','do','da','de','dos','das','ainda','enquanto','aqui','está','somente','outra','na','no','nas','nos','mas','mais','sem','com','está','em','até','as','os','pelo','rt','ou','outras','se','que','ao','à','um','uma','traz','trás','por','pela','nesta','neste','deste','desta','uma','por','pela','para','das','não','pra','ser','ficou','eu','deve','como','alto','baixo','como','através','dentre','entre','i','o','u',':','?','é','vou','tô','to','the','teve','ter','tido','tava','ta','-','.',';','É','via','são','sobre');
          
          foreach ($todas_palavras as $val1) {
              foreach ($palavras_comuns as $val2) {
                if ($val1 == $val2) {
                  $todas_palavras[$i] = false;
                }
              }
            $i++;
          }
          $i=0;
            //Eliminar conectivos e ordenar array
              foreach ($todas_palavras as $val) {
                if (!$val) {
                  unset($todas_palavras[$i]);
                }
                $i++;
              }
            $i=0;
              rsort($todas_palavras);
          //Fazer um for que percorre cada palavra e verifica a frequencia delas
          $frequencia = 1;
          $palavras_e_frequencia = array();
          foreach ($todas_palavras as $val1) {
            foreach ($todas_palavras as $key => $val2) {
              $achados = array();
              if ($val1 == $val2) {
               
                $tem = false;
                if (count($palavras_e_frequencia)>0) {
                  $j=0;
                  foreach ($palavras_e_frequencia as $val3) {
                    
                    if ($val1 == $val3["palavra"]) {
                      $tem = true; $frequencia++;
                      $palavras_e_frequencia[$j]["frequencia"] = $frequencia;
                      $palavras_e_frequencia[$j]["porcentagem"] =  (($frequencia * count($palavras_comuns))/100);
                    }
                    $j++;
                  }
                  if ($tem) {
                      //atualiza a frequencia
                   
                  }else{

                    $achados = array(
                      'palavra' => $val1,
                      'frequencia' => $frequencia,
                      'porcentagem' => false
                    );
                    array_push($palavras_e_frequencia, $achados);
                  }
                  $verificador = false;
                }else{
                    $achados = array(
                      'palavra' => $val1,
                      'frequencia' => $frequencia,
                      'porcentagem' => false
                    );
                  array_push($palavras_e_frequencia, $achados);
                }
              }
            }
            $frequencia =1;
            $i++;
          }
          $i=0;
          $repetidas = array();
         //Ordenar o array pela frequencia 
          foreach ($palavras_e_frequencia as $value) {
            if ($value["porcentagem"]) {
             $repetidas[$i] = array(
                'porcentagem' => $value["porcentagem"],
                'palavra' => $value["palavra"],
                'frequencia' => $value["frequencia"]
             );
              $i++;
            }
          }
          
/* / DIA 1*/
  //LÓGICA FEITA
/*DIA 2*/
      //order by porcentagem
       array_multisort($repetidas);
       arsort($repetidas);
       if ($_POST) {
         exit(json_encode($repetidas));
       }

  //Trata dados -> tirar ( . , / & - ); etc : complete
  //Estrutura HTML : complete
    //Talvez fazer um input para filtrar por usuário : ...
  //Transformar em Objetos PHP, Fazer requisições com o Ajax ou Axios
     /*if ($connection->getHttpRequest() == '200') {
       # code...
     }*/

       ?>    

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  
  <title>Bolsonaro</title>

  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous">
  </script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  
</head>
<body class="bg-light">
    <div class="container border border-danger form-inline shadow bg-white rounded mb-2 p-3" >
      <?php echo count($todas_palavras)."<br>"; ?>
    <?php foreach ($repetidas as $value): ?>
              <div class="col-3 border border-dark rounded bg-light border-primary  p-2">
                
                <div class="col-12">
                  <?php echo "<b>".$tweets[0]->user->name."</b>:";?>
                </div>

                <div class="col-12 p-1">
                   <?php echo $value["palavra"]; ?><br>
                   <?php echo '<b>'.$value["porcentagem"].'%</b>'; ?>
                </div>

              </div>
    <?php endforeach ?>
    </div>
    <?php foreach($tweets as $data): ?>

          <div class="container border border-primary shadow bg-white rounded mb-2 p-3" >
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
</body>
</html>