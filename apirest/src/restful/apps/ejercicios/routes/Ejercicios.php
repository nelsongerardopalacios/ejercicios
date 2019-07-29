<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Psr\Http\Message\StreamInterface;

$app->group('/ejercicios', function () use ($app) {

   $app->get('/ejercicio1', function (Request $request, Response $response, array $args) {

        try {
            $ws = json_decode(file_get_contents("https://reqres.in/api/users"),true);
            
            $paginas = $ws ["total_pages"] ;
            $porPagina = $ws ["per_page"] ; 
            $data = "";
            for($i=1; $i<=$paginas;$i++)
            {
                $mas12 = 0;
                $d = json_decode(file_get_contents("https://reqres.in/api/users?page=".$i),true);

                for($j=0; $j<$porPagina;$j++){
                    $first_name= $d["data"][$j]["first_name"];
                    $last_name= $d["data"][$j]["last_name"];
                    $nombreCompleto = $first_name.$last_name;
                    
                    if(strlen($nombreCompleto)>12){
                        $mas12++;
                    }
                }

                $porcentaje = ($mas12/$porPagina)*100;
                
                
                $data = json_decode(file_get_contents("https://reqres.in/api/users?page=".$i),true);
                $data["porcentaje"]=$porcentaje;
                $data["mas12"]=$mas12;

                $respuesta[$i-1] = $data;
            }

            if ($respuesta  == NULL){
                $response->getBody()->write('Vacio');
                return $response->withStatus(204);
            }else{
                $respuesta = $this->funciones->encode($respuesta);
                $response->getBody()->write($respuesta);
                return $response->withStatus(200);
            }

        } catch(exceptions $e) {
            echo $e->getMessage();
        }

    });



    $app->get('/ejercicio2/{pagina}', function (Request $request, Response $response, array $args) {

        //OBTENER ARGUMENTOS 
        $pagina = $this->funciones->filtrar($args['pagina']);

        try {
            $ws = json_decode(file_get_contents("https://reqres.in/api/users?page=".$pagina),true);
            
            $respuesta = $ws["data"];
            if ($respuesta  == NULL){
                $response->getBody()->write('Vacio');
                return $response->withStatus(204);
            }else{
                $respuesta = $this->funciones->encode($respuesta);
                $response->getBody()->write($respuesta);
                return $response->withStatus(200);
            }

        } catch(exceptions $e) {
            echo $e->getMessage();
        }

    });

    
});