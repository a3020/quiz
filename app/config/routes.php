<?php

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

// Application Middleware
$app->add(function (Request $request, Response $response, callable $next) {
    $uri = $request->getUri();
    $path = $uri->getPath();
    if ($path != '/' && substr($path, -1) == '/') {
        // permanently redirect paths with a trailing slash
        // to their non-trailing counterpart
        $uri = $uri->withPath(substr($path, 0, -1));
        return $response->withRedirect((string)$uri, 301);
    }

    return $next($request, $response);
});


// Application Routes
$app->get('/', 'App\Controller\QuizController:getList');
$app->get('/quiz', function($request, $response) {
    return $response->withStatus(301)->withHeader('Location', '/');
});
$app->get('/quiz/{slug}', 'App\Controller\QuizController:getQuiz');
$app->post('/quiz/{slug}', 'App\Controller\QuizController:postQuiz');