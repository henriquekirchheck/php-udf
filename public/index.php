<?php require __DIR__ . "/vendor/autoload.php";

use Rammewerk\Router\Router;

// Setup router
$container = static fn(string $class) => new $class();
$router = new Router($container);

// // Register entry points
// $router->entryPoint('/api/health', ApiController::class);
// $router->entryPoint('/api/users', UserController::class);
// $router->entryPoint('/api/users/*', UserController::class);

// // Group with shared middleware
// $router->group(function(Router $r) {
//     $r->entryPoint('/admin/users', AdminUserController::class);
//     $r->entryPoint('/admin/settings', AdminSettingsController::class);
// })->middleware([AuthMiddleware::class, AdminMiddleware::class]);

// Dispatch
$response = $router->dispatch();