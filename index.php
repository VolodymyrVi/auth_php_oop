<?php

use App\Authorization;
use App\AuthorizationException;
use App\Database;
use App\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require __DIR__ . '/vendor/autoload.php';

$loader = new FilesystemLoader('templates');
$twig = new Environment($loader);

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$session = new Session();

$sessionMiddleware = function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($session) {
    $session->start();
    $response = $handler->handle($request);
    $session->save();

    return $response;
};

$app->add($sessionMiddleware);

$config = include_once 'config/database.php';
$dsn = $config['dsn'];
$username = $config['username'];
$password = $config['password'];

$database = new Database($dsn, $username, $password);

$authorization = new Authorization($database);


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

$app->get('/register', function(ServerRequestInterface $request, ResponseInterface $response) use ($twig, $session) {
    
    $body = $twig->render('register.twig',[
        'message' => $session->flush('message'),
        'form' => $session->flush('form'),
    ]);
    
    $response->getBody()->write($body);
    
    return $response;
});

$app->post('/register-post', function(ServerRequestInterface $request, ResponseInterface $response) use ($authorization, $session) {
    
    $params = (array) $request->getParsedBody();

    try {
        $authorization->register($params);
    } catch (AuthorizationException $exception) {
        $session->setData('message', $exception->getMessage());
        $session->setData('form', $params);
        return $response->withHeader('Location', '/register')->withStatus(302);
    }
    return $response->withHeader('Location', '/')->withStatus(302);
});

$app->get('/logout', function(ServerRequestInterface $request, ResponseInterface $response) use ($twig) {
    
    return $response;
});

$app->run();