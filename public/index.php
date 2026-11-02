<?php

declare(strict_types=1);

use App\Controller\DefaultController;
use App\Controller\ReportController;
use App\Controller\SessionController;
use App\Routing;

require_once dirname(__DIR__) . '/vendor/autoload.php';

set_exception_handler(static function (Throwable $exception): void {
    error_log((string) $exception);
    http_response_code(500);
    (new DefaultController())->serverError();
});

$routing = new Routing();

$routing->get('/', DefaultController::class, 'index');
$routing->get('/about', DefaultController::class, 'about');
$routing->get('/sessions', SessionController::class, 'index');
$routing->get('/sessions/{id}', SessionController::class, 'show');
$routing->get('/sports/{sport:swim|bike|run}', SessionController::class, 'bySport');
$routing->get('/reports/{year:\d{4}}', ReportController::class, 'show');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

if (!$routing->dispatch($method, $uri)) {
    $path = parse_url($uri, PHP_URL_PATH) ?: '/';
    $allowedMethods = $routing->allowedMethods($path);

    if ($allowedMethods !== []) {
        header('Allow: ' . implode(', ', $allowedMethods));
        (new DefaultController())->methodNotAllowed($allowedMethods);
    } else {
        (new DefaultController())->notFound();
    }
}
