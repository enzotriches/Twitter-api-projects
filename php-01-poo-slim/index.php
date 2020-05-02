<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization");

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './Api/Model/TwitterConnection.php';
require './Api/Controller/Verifyer.php';
require './Api/Controller/Biscoiteiro.php';


require './vendor/autoload.php';

$c = new \Slim\Container(); 

$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode(array("error"=>"Nada aqui...")));
    };
};

$c['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return $response->withStatus(500)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode(array("error"=>"Houve um erro inesperado")));
    };
};

$c['notAllowedHandler'] = function ($c) {
    return function ($request, $response, $methods) use ($c) {
        return $response->withStatus(405)
            ->withHeader('Allow', implode(', ', $methods))
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode(array("error"=>"Tipo de request errada")));
    };
};


$app = new \Slim\App($c);
  

$app->get('/', function ($request, $response, $args) {
    require_once('./View/index.php');
    return  $response->withStatus(200)->write("");
});

$app->get('/auth/{auth}/users/{user}', function ($request, $response, $args) {
    //Esse auth ai é bizarro? Sim, não se usa isso ai.
    //Então pq ta ai? Está ai 
    $auth = trim(stripslashes($args['auth']));  
    $who = trim(stripcslashes($args['user']));
    $vf = new Verifyer($auth);
    
    $tweets = $vf->checkRepeatedWords($who,200,false);
     if(is_array($tweets)){
      $tweets = json_encode($tweets);
     }
    $response
      ->withStatus(200)
      ->withHeader('Content-Type', 'application/json')
      ->write($tweets);
    return $response;
});


$app->run();

?>

  
