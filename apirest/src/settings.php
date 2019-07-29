<?php
define('APP_ROOT', __DIR__);

$desarrollolocal = $_SERVER['HTTP_HOST'];

$servidor   = 'localhost';
$pos = strpos($desarrollolocal, $servidor);

if ($pos !== false) {
    $localhost = true;
}else{
    $localhost = false;
}


return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => APP_ROOT . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : APP_ROOT . '/logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'bd' => '',
        'valor' => '',
        'localhost' => $localhost,
        'ip' => $_SERVER["REMOTE_ADDR"],
        'pc' => $_SERVER['REMOTE_ADDR'],
        'login'=> "",
        'logout'=> "",
        'token'=> '',
    	'fotos'=> '',
        'tokenFoto'=> '',
        'rutaFotos'=> APP_ROOT . '/../../photos/',
        'idApp'=> '',
        'usuario'=>[
            'nombre' => '',
            'nombreCompleto' => '',
            'email' => '',
            'estructuraOrganicaId' => '',
            'estructuraOrganica' => '',
            'perfiles' => [],
            'legajo' => ''
        ]
        
        
        
    ],
];
