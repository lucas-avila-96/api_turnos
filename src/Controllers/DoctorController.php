<?php

namespace App\Controllers;

use App\Services\DoctorService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DoctorController
{
    private $app;
    private $doctorService;

    public function __construct($app, $entityManager)
    {
        $this->app = $app;
        $this->doctorService = new DoctorService($entityManager);
    }

    public function buildRoutes()
    {
        $this->app->get('/doctors', [$this, 'getAll']);
        $this->app->get('/doctors/{doctorId}', [$this, 'getById']);
        $this->app->post('/doctors', [$this, 'create']);
        $this->app->put('/doctors/{doctorId}', [$this, 'update']);
        $this->app->delete('/doctors/{doctorId}', [$this, 'delete']);
    }

    public function getAll(Request $request, Response $response, $args)
    {
        $doctorsList = json_encode($this->doctorService->getDoctors(), JSON_PRETTY_PRINT);
        $response->getBody()->write($doctorsList);
        return $response;
    }

    public function getById(Request $request, Response $response, $args)
    {
        $doctorId = $args['doctorId'];
        $doctor = json_encode($this->doctorService->getDoctor($doctorId), JSON_PRETTY_PRINT);
        $response->getBody()->write($doctor);
        return $response;
    }

    public function create(Request $request, Response $response, $args)
    {
        $doctor = json_decode($request->getBody(), true);
        $doctor = json_encode($this->doctorService->create($doctor), JSON_PRETTY_PRINT);
        $response->getBody()->write($doctor);
        return $response;
    }

    public function update(Request $request, Response $response, $args)
    {
        $doctor = json_decode($request->getBody(), true);
        $doctorId = $args['doctorId'];
        $doctor = json_encode($this->doctorService->update($doctorId, $doctor), JSON_PRETTY_PRINT);
        $response->getBody()->write($doctor);
        return $response;
    }

    public function delete(Request $request, Response $response, $args)
    {
        $doctorId = $args['doctorId'];
        $doctor = json_encode($this->doctorService->delete($doctorId), JSON_PRETTY_PRINT);
        $response->getBody()->write($doctor);
        return $response;
    }

    
}
