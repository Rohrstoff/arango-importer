<?php

require 'vendor/autoload.php';

use App\Database\ArangoConnection;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$router = new AltoRouter();
$loader = new FilesystemLoader( 'views' );
$twig = new Environment($loader);

$con = new ArangoConnection();

$router->map('GET', '/', function () use ($twig, $con)
{
	echo $twig->render( 'index.html', ['message' => $con->helloWorld()] );
});

$router->map('GET', '/hello-world', function () use ($twig, $con)
{
	echo 'Hello World!';
});

// match current request url
$match = $router->match();

// call closure or throw 404 status
if ( is_array($match) && is_callable( $match['target'] ) )
{
	call_user_func_array( $match['target'], $match['params'] );
}
else
{
	// no route was matched
	header( $_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
}
