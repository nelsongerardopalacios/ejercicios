<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

//use Slim\Http\Request;
//use Slim\Http\Response;
// PSR 7 standard.
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/*
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});
*/

//$app->add(function ($req, $res, $next) {
$app->add(function (Request $request, Response $response, $next) {
    //$response = $next($req, $res);
    $response = $next($request, $response);
    return $response
            ->withHeader('Access-Control-Allow-Origin','*') //'SAMEORIGIN')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->add(function (Request $request, Response $response, $next) {

    $token = $request->getHeaderLine('psa-auth-token');
    $idApp = $request->getHeaderLine('id-App');
    $ipApp = $_SERVER["REMOTE_ADDR"];
    if ($this->get('settings')['localhost']){
        $siga = false;
    }else{
        $siga = true;    
    }
    

    if ($siga){

        $url = $this->get('settings')['login'].str_replace('"', "",$token)."/".$ipApp."/".$idApp;

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
             //return $response->withJson($usuario,200);
            $response = $next($request, $response);
            return $response;
        }else{
            
                $response->withStatus(401);
                return $response->withRedirect('/');

        }

    }else{
        //$response->withStatus(401);
        //return $response->withRedirect('/');
        
        return $next($request, $response);
    };
});

//$app->add($container->get('csrf'));

//$app->add(function ($request, $response, $next) use ($container) {
$app->add(function (Request $request, Response $response, $next) use ($container) {
    // Default status code.
    $status = 200;
    // Catch errors.
    try {
        $response = $next($request, $response);
        $status = $response->getStatusCode();
        // If it is 404, throw error here.
        switch ($status){
            case 404:
                throw new \Exception('Pagina No Encontrada', 404);
                // A 404 should be invoked.
                // Note since it is to be taken care by the exception below
                // so comment this custom 404.
                // $handler = $container->get('notFoundHandler');
                // return $handler($request, $response);
            break;
            case 403:
                throw new \Exception('Forbidden', 403);
            break;
            case 500:
                throw new \Exception('Error Servidor', 500);
            break;
        }


    } catch (\Exception $error) {
        $status = $error->getCode();
        $data = [
            "status" => $error->getCode(),
            "message" => $error->getMessage()

        ];
        //$response->getBody()->write(json_encode($data));
        $this->renderer->render($response, 'error.phtml', $data);
    };
    return $response
        ->withStatus($status)
        //->withHeader('Content-type', 'application/json');
        ->withHeader('Content-type', 'text/html');


});

// Add the middleware
$app->add(function ($request, $response, $next) {
    // add media parser
    $request->registerMediaTypeParser(
        //"text/javascript",
        'application/x-www-form-urlencoded',
        function ($input) {
            return json_decode($input, true);
        }
    );

    return $next($request, $response);
});

// Catch-all route to serve a 404 Not Found page if none of the routes match
// NOTE: make sure this route is defined last
/*
$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
    $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});
*/
