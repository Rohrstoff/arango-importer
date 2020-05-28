<?php

require 'vendor/autoload.php';

use App\Database\ArangoConnection;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader( 'views' );
$twig = new Environment($loader);

$con = new ArangoConnection();

echo $twig->render( 'index.html', ['message' => $con->helloWorld()] );
