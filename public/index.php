<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use App\Services\DoctorService;
use App\Services\SpecialtyService;
use App\Services\AppointmentService;
use App\Controllers\DoctorController;
use App\Controllers\SpecialtyController;
use App\Controllers\AppointmentController;


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/bootstrap.php';

if (!isset($entityManager)) {
    echo "Entity manager is not set.\n";
    return;
}

$doctorService = new DoctorService($entityManager);
$specialtyService = new SpecialtyService($entityManager);
$appointmentService = new AppointmentService($entityManager);


$app = AppFactory::create();

$app->addBodyParsingMiddleware();

// Add the RoutingMiddleware before the CORS middleware
// to ensure routing is performed later
$app->addRoutingMiddleware();

// Add the ErrorMiddleware before the CORS middleware
// to ensure error responses contain all CORS headers.
$app->addErrorMiddleware(true, true, true);

// This CORS middleware will append the response header
// Access-Control-Allow-Methods with all allowed methods

// Doctor Controller
$doctorController = new DoctorController($app, $entityManager);
$doctorController->buildRoutes();

// Specialty Controller
$specialtyController = new SpecialtyController($app, $entityManager);
$specialtyController->buildRoutes();

// Appointment Controller
$appointmentController = new AppointmentController($app, $entityManager);
$appointmentController->buildRoutes();


$app->add(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($app): ResponseInterface {
    if ($request->getMethod() === 'OPTIONS') {
        $response = $app->getResponseFactory()->createResponse();
    } else {
        $response = $handler->handle($request);
    }

    $response = $response
        ->withHeader('Access-Control-Allow-Credentials', 'true')
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', '*')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
        ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->withHeader('Pragma', 'no-cache');

    if (ob_get_contents()) {
        ob_clean();
    }

    return $response;
});
set_time_limit(300);

$app->run();
