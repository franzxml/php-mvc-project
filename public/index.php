<?php
// ======================================================
// 1. Load Composer Autoload
// ======================================================
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;

// ======================================================
// 2. Load Core Classes (jika belum ter-autoload oleh Composer)
// ======================================================
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/Controller.php';

// ======================================================
// 3. Inisialisasi Router
// ======================================================
$router = new Router();

// ======================================================
// 4. Define Routes
// ======================================================
$router->get('/', 'HomeController@index');
$router->get('/users', 'UserController@index');
$router->get('/users/{id}', 'UserController@show');
$router->post('/users', 'UserController@store');

// ======================================================
// 5. Jalankan Resolver
// ======================================================
$router->resolve();
