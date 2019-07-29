<?php

//use Slim\Http\Request;
//use Slim\Http\Response;
// PSR 7 standard.
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**********************************************************************************************************/
/**********************************************************************************************************/
/**********************************************************************************************************/
/**********************************************************************************************************/
/**********************************************************************************************************/

$app->get('/getusuario', function (Request $request, Response $response, $args) {

    //$token = $request->getHeaderLine('psa-auth-token');
    $cookies = $request->getCookieParams();
    $this->get('settings')['token'] = $cookies['psa-auth-token'];
    $this->get('settings')['idApp'] = $request->getHeaderLine('id-App');
   
    $url = $this->get('settings')['login'].str_replace('"', "",$this->get('settings')['token'])."/".$this->get('settings')['ip']."/".$this->get('settings')['idApp'];
    
    $solicitud = curl_init();
    curl_setopt_array($solicitud, array
    (
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_SSL_VERIFYPEER => 1,

    ));

    // Execute request and get response and status code
    $respuesta = curl_exec($solicitud);
    $httpCode   = curl_getinfo($solicitud, CURLINFO_HTTP_CODE);
    //$validacion = json_decode($respuesta,true);
    $usuario = json_decode($respuesta,true);
    // Close connection
    curl_close($solicitud);
    
    
    if (((int) $httpCode == 200) || ($usuario <> null)) {
         return $response->withJson($usuario,200);
    }else{
        $response->withStatus(401);
        return $response->withRedirect('/');
    }

});

/**********************************************************************************************************/
/**********************************************************************************************************/
/**********************************************************************************************************/
/**********************************************************************************************************/
/**********************************************************************************************************/

$app->get('/logout', function (Request $request, Response $response, $args) {
        
    $solicitud = curl_init();
    curl_setopt_array($solicitud, array
    (
        CURLOPT_URL => $this->get('settings')['logout'],
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_SSL_VERIFYPEER => 1,

    ));

    // Execute request and get response and status code
    $respuesta = curl_exec($solicitud);
    $httpCode   = curl_getinfo($solicitud, CURLINFO_HTTP_CODE);
    $validacion = json_decode($respuesta,true);
    // Close connection
    curl_close($solicitud);
    
    $response->withStatus(401);
    return $response->withRedirect('/');
    
});

/**********************************************************************************************************/
/**********************************************************************************************************/
/**********************************************************************************************************/
/**********************************************************************************************************/
/**********************************************************************************************************/

$app->get('/token', function (Request $request, Response $response, array $args) {

            $token = $request->getHeaderLine('psa-auth-token');
            $row = $this->funciones->encode($$token);
            $response->getBody()->write(json_encode($token));
            return $response->withStatus(200);


});

/**********************************************************************************************************/
/**********************************************************************************************************/
/**********************************************************************************************************/
/**********************************************************************************************************/
/**********************************************************************************************************/

$app->get('/ipself', function (Request $request, Response $response, array $args) {

            $token = $_SERVER["REMOTE_ADDR"];
            //$row = $this->funciones->encode($$token);
            $response->getBody()->write(json_encode($token));
            return $response->withStatus(200);


});

/**********************************************************************************************************/
/**********************************************************************************************************/
/**********************************************************************************************************/
/**********************************************************************************************************/
/**********************************************************************************************************/






