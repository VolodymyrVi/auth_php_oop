<?php

use App\Database;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require __DIR__ . '/vendor/autoload.php';

$loader = new FilesystemLoader('templates');
$twig = new Environment($loader);

$app = AppFactory::create();

$config = include_once 'config/database.php';
$dsn = $config['dsn'];
$username = $config['username'];
$password = $config['password'];

$datebase = new Database($dsn, $username, $password);

$app->get('/', function(ServerRequestInterface $request, ResponseInterface $response) use ($twig) {
    
    $body = $twig->render('index.twig');

    $response->getBody()->write($body);
    
    return $response;
});

$app->get('/login', function(ServerRequestInterface $request, ResponseInterface $response) use ($twig) {
    
    $body = $twig->render('login.twig');
    
    $response->getBody()->write($body);
    
    return $response;
});

$app->post('/login-post', function(ServerRequestInterface $request, ResponseInterface $response){
        
    return $response;
});

$app->get('/register', function(ServerRequestInterface $request, ResponseInterface $response) use ($twig) {
    
    $body = $twig->render('register.twig');
    
    $response->getBody()->write($body);
    
    return $response;
});

$app->post('/register-post', function(ServerRequestInterface $request, ResponseInterface $response){
    
    return $response;
});

$app->get('/logout', function(ServerRequestInterface $request, ResponseInterface $response) use ($twig) {
    
    return $response;
});

$app->run();