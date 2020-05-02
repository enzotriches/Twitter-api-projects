<?php

require './vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterConnection{

	private $token_de_acesso;
	private $token_secreto_de_acesso;
    private $key = "a1s2d3f4g5h6";
	public $connection;

	public function __construct(){
		/* Mude para suas chaves */
		$this->token_de_acesso = "{TOKEN DE ACESSO PUBLICO }";
		$this->token_secreto_de_acesso = "[TOKEN DE ACESSO PRIVADO]";
		$this->connection = new TwitterOAuth("{API KEY}", "{API SECRET KEY}", $this->token_de_acesso, $this->token_secreto_de_acesso);
	}

	/* Conexão Singletoon */
	public function getTwitterConnection($k=false){
		try {
    		  if($k == $this->key){
         	      return $this->connection;
     		  }else{
     		      exit(json_encode(array("error"=>"Erro em autenticar...")));
     		  }
		} catch (PDOException $e) {
		   exit(json_encode(array("error"=>"Conexão muito fraca...")));
		}
	}
}

