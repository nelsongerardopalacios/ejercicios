<?php

//use Slim\Http\Request;
//use Slim\Http\Response;
// PSR 7 standard.
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Psr\Http\Message\StreamInterface;
//use \Medoo\Medoo;


$app->group('/ejercicios', function () use ($app) {

    $app->get('/getAll', function (Request $request, Response $response, array $args) {

        //OBTENER ARGUMENTOS
        
        //$headers = $request->getHeaders();
        //$data = $request->headers;
        try {
            //$data = json_decode(file_get_contents("https://reqres.in/api/users"),true);

            $ws = json_decode(file_get_contents("https://reqres.in/api/users"),true);
            
            $paginas = $ws ["total_pages"] ;
            $porPagina = $ws ["per_page"] ; 
            $data = "";
            for($i=1; $i<=$paginas;$i++)
            {
                
                //$d = json_decode(file_get_contents("https://reqres.in/api/users?page=".$i),true);
                //print_r("este es D= ");
                //print_r( $d);
                //print_r($d["data"]);
                $d = file_get_contents("https://reqres.in/api/users?page=".$i);
                //$data.= $d["data"];
            }
            echo $d;
            /*echo ("aca viene el data: ");
                echo ($data);*/
            /*if ($data  == NULL){
                $response->getBody()->write('Vacio');
                return $response->withStatus(204);
            }else{
                $data = $this->funciones->encode($data);
                $response->getBody()->write($data);
                return $response->withStatus(200);
            }*/

        } catch(exceptions $e) {
            echo $e->getMessage();
        }

    });


    
    $app->get('/ejercicio1', function (Request $request, Response $response, array $args) {

        //OBTENER ARGUMENTOS
        
        //$headers = $request->getHeaders();
        //$data = $request->headers;
        //$respuesta="";
        try {
            //$data = json_decode(file_get_contents("https://reqres.in/api/users"),true);

            //$ws = json_decode(file_get_contents("http://localhost/ejercicios/apirest/ejercicios/datos"),true);
            $ws = json_decode(file_get_contents("https://reqres.in/api/users"),true);
            
            $paginas = $ws ["total_pages"] ;
            $porPagina = $ws ["per_page"] ; 
            $data = "";
            for($i=1; $i<=$paginas;$i++)
            {
                $mas12 = 0;
                $d = json_decode(file_get_contents("https://reqres.in/api/users?page=".$i),true);
                /*echo("Esta es la pagina: ".$i);
                echo("<br>");*/
                //print_r("este es D= ");
                //print_r($d["data"]);
                for($j=0; $j<$porPagina;$j++){
                    $first_name= $d["data"][$j]["first_name"];
                    $last_name= $d["data"][$j]["last_name"];
                    $nombreCompleto = $first_name.$last_name;
                    
                    if(strlen($nombreCompleto)>12){
                        $mas12++;
                    }
                }
                /*echo("por Pagina: ");
                echo($porPagina);
                echo("<br>");
                echo("mas de 12: ");
                echo($mas12);*/
                /*if($mas12>0){
                    $porcentaje = ($porPagina/$mas12)*100;
                }
                else{
                    $porcentaje = 0;
                }*/

                $porcentaje = ($mas12/$porPagina)*100;
                
                /*echo("<br>");
                echo("el porcentaje es ");
                echo($porcentaje);
                echo("<br>");*/

                /*$first_name=;
                $last_name=;
                $nombreCompleto = $first_name+$last_name;
                count($nombreCompleto);
                if($nombreCompleto>12){
                    $mas12++;
                }
                */
                $data = json_decode(file_get_contents("https://reqres.in/api/users?page=".$i),true);
                //$data .= file_get_contents("https://reqres.in/api/users?page=".$i);
                //$data->data;
                $data["porcentaje"]=$porcentaje;
                $data["mas12"]=$mas12;

                $respuesta[$i-1] = $data;
            }
            //$d = json_decode($data);
            //echo ($d);
            //echo $data["data"][0];
            //var_dump($respuesta);
            //echo $data;


            //echo ("aca viene el data: ");
                //echo ($data);
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
        //echo "pagina: ";
        //print_r($pagina);
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