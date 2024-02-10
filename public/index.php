<?php

use Lbeaudln\Gestionnaire\Core\Router;

require __DIR__ . '/../vendor/autoload.php';

$router = new Router();

// get
$router->get('/', 'Lbeaudln\Gestionnaire\Controllers\UserController@login');
$router->get('/login', 'Lbeaudln\Gestionnaire\Controllers\UserController@login');
$router->get('/account/information', 'Lbeaudln\Gestionnaire\Controllers\UserController@information');
$router->get('/account/security', 'Lbeaudln\Gestionnaire\Controllers\UserController@security');
$router->get('/account/users', 'Lbeaudln\Gestionnaire\Controllers\UserController@users');
$router->get('/account/users/add', 'Lbeaudln\Gestionnaire\Controllers\UserController@addUser');
$router->get('/reset-information', 'Lbeaudln\Gestionnaire\Controllers\Reset\Information@execute');
$router->get('/dashboard', 'Lbeaudln\Gestionnaire\Controllers\UserController@dashboard');
$router->get('/customer/add', 'Lbeaudln\Gestionnaire\Controllers\CustomerController@add');
$router->get('/customer/delete', 'Lbeaudln\Gestionnaire\Controllers\CustomerController@delete');
$router->get('/customer/edit', 'Lbeaudln\Gestionnaire\Controllers\CustomerController@edit');
$router->get('/customer/edit/form', 'Lbeaudln\Gestionnaire\Controllers\CustomerController@editForm');
$router->get('/account/comments', 'Lbeaudln\Gestionnaire\Controllers\CommentController@comments');
$router->get('/mortgages/create', 'Lbeaudln\Gestionnaire\Controllers\MortgageController@create');
// post
$router->post('/login', 'Lbeaudln\Gestionnaire\Controllers\UserController@login');
$router->post('/logout', 'Lbeaudln\Gestionnaire\Controllers\UserController@logout');
$router->post('/reset-password', 'Lbeaudln\Gestionnaire\Controllers\Reset\Information@execute');
$router->post('/confirm-opt', 'Lbeaudln\Gestionnaire\Controllers\Reset\Code@execute');
$router->post('/customer/add', 'Lbeaudln\Gestionnaire\Controllers\CustomerController@add');
$router->post('/customer/delete', 'Lbeaudln\Gestionnaire\Controllers\CustomerController@delete');
$router->post('/customer/edit/form', 'Lbeaudln\Gestionnaire\Controllers\CustomerController@editForm');
$router->post('/customer/view', 'Lbeaudln\Gestionnaire\Controllers\CustomerController@view');
$router->post('/account/update', 'Lbeaudln\Gestionnaire\Controllers\UserController@update');
$router->post('/account/security', 'Lbeaudln\Gestionnaire\Controllers\UserController@security');
$router->post('/account/users/delete', 'Lbeaudln\Gestionnaire\Controllers\UserController@removeUser');
$router->post('/account/users/add', 'Lbeaudln\Gestionnaire\Controllers\UserController@addUser');
$router->post('/comments/add', 'Lbeaudln\Gestionnaire\Controllers\CommentController@add');
$router->post('/comments/delete', 'Lbeaudln\Gestionnaire\Controllers\CommentController@delete');
$router->post('/mortgages/create', 'Lbeaudln\Gestionnaire\Controllers\MortgageController@create');
$router->post('/mortgages/delete', 'Lbeaudln\Gestionnaire\Controllers\MortgageController@delete');

// Process the request
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

$router->dispatch($requestUri, $requestMethod);