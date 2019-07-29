<?php

// DIC configuration
define ( 'ADODB_ASSOC_CASE', 0 );
require __DIR__ . "/Clases/inputfilter-xss/inputfilter/src/InputFilter.php";
require __DIR__ . "/Clases/funciones/funciones/src/Funciones.php";

require __DIR__ . "/../adodb5/adodb.inc.php";
require __DIR__ . "/../adodb5/adodb-active-record.inc.php";
// require __DIR__ . "/../adodb5/adodb-errorhandler.inc.php";
require __DIR__ . "/../adodb5/adodb-exceptions.inc.php";

// uncomment the following if you want to test exceptions
/*
 * if (@$_GET['except']) {
 * if (PHP_VERSION >= 5) {
 * require __DIR__ . "/../adodb5/adodb-exceptions.inc.php";
 * //echo "<h3>Exceptions included</h3>";
 * }
 * }
 */

use InputFilter\InputFilter;
use Funciones\Funciones;

$container = $app->getContainer ();

// view renderer
$container ['renderer'] = function ($c) {
	$settings = $c->get ( 'settings' ) ['renderer'];
	return new Slim\Views\PhpRenderer ( $settings ['template_path'] );
};

// monolog
$container ['logger'] = function ($c) {
	$settings = $c->get ( 'settings' ) ['logger'];
	$logger = new Monolog\Logger ( $settings ['name'] );
	$logger->pushProcessor ( new Monolog\Processor\UidProcessor () );
	$logger->pushHandler ( new Monolog\Handler\StreamHandler ( $settings ['path'], $settings ['level'] ) );
	return $logger;
};

// Register provider
$container ['flash'] = function () {
	return new \Slim\Flash\Messages ();
};

/*
 * //CSRF
 * $container['csrf'] = function ($c) {
 * return new \Slim\Csrf\Guard;
 * };
 */
/*
 * $container['csrf'] = function ($c) {
 * $guard = new \Slim\Csrf\Guard();
 * $guard->setFailureCallable(function ($request, $response, $next) {
 * $request = $request->withAttribute("csrf_status", false);
 * return $next($request, $response);
 * });
 * return $guard;
 * };
 */

// XSS
$container ['xss'] = function ($c) {
	return new InputFilter ();
};

// Funciones (XSS entre otros)
$container ['funciones'] = function ($c) {
	return new Funciones ();
};

/*
 * $container['errorHandler'] = function ($c) {
 * return function ($request, $response, $exception) use ($c) {
 *
 * $data = [
 * "status" => 500,
 * "message" => 'Algo salió mal' //
 *
 * ];
 * $c->renderer->render($response, 'error.phtml', $data);
 * return $response->withStatus(500)
 * ->withHeader('Content-Type', 'text/html')
 * ->write('MIKIMA'.$exception->getMessage());
 *
 * };
 * };
 */

$container ['database'] = function ($c) {

        if ($c->get('settings')['localhost']){
            $archivo = fopen ( __DIR__ . "/../db-dir/apirest.json", "r" );
        }else{
            $archivo = fopen ( "/db-dir/conexion/apirest.json", "r" );
        }
    
	$contenido = stream_get_contents ( $archivo );
	fclose ( $archivo );
	$variables = json_decode ( $contenido, true );

	$bd = $c->get ( 'settings' ) ['bd'];

	switch ($variables [$bd] ["type"]) {
		case 'oracle' :
		case 'oci8' :
			$driver = 'oci8';
			break;
        case 'mysql' :
		case 'mysqli' :
			$driver = 'mysqli';
			break;
		case 'postgres' :
			$driver = 'postgres9';
			break;
		case 'pdo_pgsql' :
			$driver = 'postgres9';
			break;
		case 'sqlserver' :
			$driver = 'mssqlnative';
			break;
		case 'sqlite' :
			$driver = 'sqlite3';
			break;
		case 'ldap' :
			$driver = 'ldap';
			break;
		default :
			break;
	}

	$db = NewADOConnection ( $driver );
    switch ($driver) {
        case 'oci8' :
            $db->setCharset ( 'AL32UTF8' );
            break;
        case 'mssqlnative' :
            $db->setConnectionParameter ( 'CharacterSet', 'UTF-8' );
            break;
        default :
            $db->setConnectionParameter ( 'CharacterSet', 'UTF-8' );
        break;
    }

    $db->connect ( $variables [$bd] ["host"], $variables [$bd] ["username"], $variables [$bd] ["password"], $variables [$bd] ["dbname"] );
    // $db->debug=1;
    $ADODB_LANG = 'es';
    ADOdb_Active_Record::SetDatabaseAdapter ( $db );
    if ($driver == 'mysqli'){
            $db->execute("SET NAMES utf8");
    }

    // ADODB_Active_Record::$_quoteNames = true;
    return $db;
};