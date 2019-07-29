<?php
/*
use Slim\Http\Request;
use Slim\Http\Response;
use \Psr\Http\Message\StreamInterface;

// Routes

$app->add(function (Request $request, Response $response, $next) {
    //$response->getBody()->write('BEFORE');
    $response = $next($request, $response);
    //$response->getBody()->write('AFTER');
    
    return $response;
    });
*/

require __DIR__ .'/fwk/autenticacion.php';
require __DIR__ .'/restful/apps/confroutes.php';
//require __DIR__ .'/../../restful/apps/confroutes.php';




