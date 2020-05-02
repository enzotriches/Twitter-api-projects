<?php

class Verifyer{
	public $connection;
	public $access;
	public $tweets = array();	
	public $palavras_comuns = array();
	public $repetidas = array();
  public $palavras = array();
	// CONSTRUTOR : ESTABELECE A CONEXÃO COM TWITTER API E APLICA RESTRIÇÕES DE PALAVRAS 
	public function __construct($key = false){
		
     $this->connection = new TwitterConnection();
		 $this->access = $this->connection->getTwitterConnection($key);
		 $this->palavras_comuns = array('a','e','o','para','nos','do','da','de','dos','das','ainda','enquanto','aqui','está','somente','outra','na','no','nas','nos','mas','mais','sem','com','está','em','até','as','os','pelo','rt','ou','outras','se','que','ao','à','um','uma','traz','trás','por','pela','nesta','neste','deste','desta','uma','por','pela','para','das','não','pra','ser','ficou','eu','deve','como','alto','baixo','como','através','dentre','entre','i','o','u',':','?','é','vou','tô','to','the','teve','ter','tido','tava','ta','tem','tinha','deste','-','.',';','É','via','são','sobre','q','só','p','me','n','"','Eu','Me','Mim','°','Tu','Te','Ti','singular','Ele','/','Ela','Se', 'Lhe', 'O','A','Si','Ele','Ela','Nós','Nos','Nós','Vós','Vos','Vós','Eles','Elas','Se','Lhes',' Os','As','Sí','Eles','Elas','meu','vai','quando','minha','seu','b','c','d','e','f','g','h');

	}
	//PEGA OS TWEETS
	public function getTweetsFromUserTimeline($who = "jairbolsonaro", $quantity = 50, $isReplies = false){
		$this->tweets = $this->access->get(
			"statuses/user_timeline",
			[
				"count" => $quantity,
				"exclude_replies" => $isReplies,
				"screen_name" => $who
			]
		);
		if (isset($this->tweets['errors'])) {
			$this->tweets = array();
		}
		return $this->tweets;
	}
	//CHECA AS PALAVRAS REPETIDAS
  public function checkRepeatedWords($who = "jairbolsonaro", $quantity = 50, $isReplies = false){
     
          //Pega Tweets
          $tweets = $this->getTweetsFromUserTimeline($who,$quantity,$isReplies);

          //Juntar todo o texto em uma variavel
          $todo_texto = $this->checkRepeatedWords_bind_all();

          //Filtra as palavras
          $todas_palavras = $this->checkRepeatedWords_filter_words($todo_texto);
              
          //Fazer um for que percorre cada palavra e verifica a frequencia delas
          $palavras_e_frequencia = $this->checkRepeatedWords_check_frequency($todas_palavras);
          
          //Ordena array pela frequencia 
          $repetidas = $this->checkRepeatedWords_order_by_frequency($palavras_e_frequencia);

           $retorno = array(
            "tweetsTotais" => $this->counter("tweets"),
            "repetidasTotais" => $this->counter("repetidas"),
            "palavrasTotais" => $this->counter("palavras"),
            "data" => $repetidas
           );
           return $retorno;
  }
    //COLOCA TODOS OS TWEETS EM UM TEXTO SÓ
  	public function checkRepeatedWords_bind_all(){
  		$todo_texto = '';

  			foreach ($this->tweets as $value) {
             
              	$todo_texto .= ' '.$value->text.' ';

           	 }
          //var_dump($todo_texto);
  		return $todo_texto;
  	}
  	//FILTRA AS PALAVRAS - TIRANDO CONECTIVOS E PONTUAÇÃO
  	public function checkRepeatedWords_filter_words($todo_texto){

   		//Separar as palavras dessa variavel com um split a cada '' 
            $todas_palavras = explode(' ', $todo_texto);
            $i=0;
              //Deixar cada palavra em minúsculo
              foreach ($todas_palavras as $value) {
              	$simbolos = array(',','.','/','%','&','*','$',':',';','?','(',')','*','}','{','|');
                
                  $valfenda = str_ireplace($simbolos, " ", $value);
              
                  $todas_palavras[$i] = strtolower(trim($valfenda));
               
               $i++;
              }
            $i=0;  

            //Verificar conectivos e palavras comuns
  		  $palavras_comuns = $this->palavras_comuns;          
            foreach ($todas_palavras as $val1) {
                foreach ($palavras_comuns as $val2) {
                  if (trim(strtolower($val1)) == trim(strtolower($val2))) {
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
          $this->palavras = $todas_palavras;
           return $todas_palavras;
  	}
  	//VERIFICA A FREQUENCIA DAS PALAVRAS
  	public function checkRepeatedWords_check_frequency($todas_palavras){
  		$i = 0;
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
                        $palavras_e_frequencia[$j]["porcentagem"] =  true;
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
            return $palavras_e_frequencia;
  	}
    //ORDENA AS PALAVRAS PELA FREQUENCIA
  	public function checkRepeatedWords_order_by_frequency($palavras_e_frequencia){
  		  $i=0;
            $repetidas = array();
           //Ordenar o array pela frequencia 
            foreach ($palavras_e_frequencia as $value) {
              if ($value["porcentagem"]) {
               $repetidas[$i] = array(
                  'porcentagem' => (100*($value["frequencia"]))/count($this->palavras),
                  'palavra' => $value["palavra"],
                  'frequencia' => $value["frequencia"]
               );
                $i++;
              }
            }
            $i=0;
            foreach ($repetidas as $key => $value) {
              $repetidas[$i] = array(
                  'porcentagem' => number_format((100*($value["frequencia"]))/count($this->palavras),2),
                  'palavra' => $value["palavra"],
                  'frequencia' => $value["frequencia"]
               );
                $i++;
            }
         	 
         	 array_multisort($repetidas);
        	 arsort($repetidas);

         	 
            //for()
        	 $this->repetidas = $repetidas;
        	 return $this->repetidas;
  	}
	
  //RETORNA A QUANTIDADE DE  X 
	public function counter($what = 'tweets'){
		$valor = null;
		switch ($what) {
			case 'tweets':
				$valor = count($this->tweets);
				break;
			case 'repetidas':
				$valor = count($this->repetidas);
				break;
			case 'palavras':
				$valor = count($this->palavras);
				break;
			
			default:
				
				break;
		}
		return $valor;
	}


	

	
}

