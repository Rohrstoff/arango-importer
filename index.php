<?php

require 'vendor/autoload.php';

use App\Controller\ImportController;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$router = new AltoRouter();
$loader = new FilesystemLoader( 'views' );
$twig = new Environment($loader);

$router->map('GET', '/', function () use ($twig)
{
	echo $twig->render( 'index.html' );
});

$router->map( 'POST', '/upload', function () use ($twig)
{
	$controller = new ImportController();
	$controller->handleUpload();

	echo $twig->render( 'upload.html' );
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
