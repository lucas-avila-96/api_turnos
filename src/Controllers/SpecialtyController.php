<?php

namespace App\Controllers;

use App\Services\SpecialtyService;
use App\Services\DoctorService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SpecialtyController
{
    private $app;
    private $specialtyService;
    private $doctorService;
    private $entityManager;

    public function __construct($app, $entityManager)
    {
        $this->app = $app;
        $this->entityManager = $entityManager;
        $this->specialtyService = new SpecialtyService($entityManager);
        $this->doctorService = new DoctorService($entityManager);

    }

    public function buildRoutes()
    {
        $this->app->get('/', [$this, 'welcome']);
        $this->app->get('/specialties', [$this, 'getAll']);
        $this->app->get('/specialties/{specialtyId}', [$this, 'getById']);
        $this->app->post('/specialties', [$this, 'create']);
        $this->app->put('/specialties/{specialtyId}', [$this, 'update']);
        $this->app->delete('/specialties/{specialtyId}', [$this, 'delete']);
        $this->app->get('/specialties/{specialtyId}/doctors', [$this, 'getDoctorsBySpecialty']);
    }

    function getAll(Request $request, Response $response, $args)
    {
        $specialties = $this->specialtyService->getSpecialties();
        $response->getBody()->write(json_encode($specialties, JSON_PRETTY_PRINT));
        return $response;
    }

    function welcome(Request $request, Response $response, $args)
    {
        $response->getBody()->write("Hello, world!");
        return $response;
    }

    function getById(Request $request, Response $response, $args)
    {
        $specialtyId = $args['specialtyId'];
        $specialty = $this->specialtyService->getSpecialty($specialtyId);
        if (!$specialty) {
            $response->getBody()->write(json_encode(['error' => 'Especialidad no encontrada']));
            return $response;
        }
        $response->getBody()->write(json_encode($specialty));
        return $response;
    }

    function create(Request $request, Response $response, $args)
    {
        $data = json_decode($request->getBody(), true);
        $specialty = $this->specialtyService->create($data);
        $response->getBody()->write(json_encode($specialty));
        return $response;
    }

    function update(Request $request, Response $response, $args)
    {
        $specialtyId = $args['specialtyId'];
        $data = json_decode($request->getBody(), true);
        $result = $this->specialtyService->updateSpecialty($specialtyId, $data);
        if (!$result['success']) {
            $response->getBody()->write(json_encode(['error' => $result['message']]));
            return $response;
        }
        $response->getBody()->write(json_encode($result['data']));
        return $response;
    }

    function delete(Request $request, Response $response, $args)
    {
        $specialtyId = $args['specialtyId'];
        $result = $this->specialtyService->deleteSpecialty($specialtyId);
        if (!$result['success']) {
            $response->getBody()->write(json_encode(['error' => $result['message']]));
            return $response;
        }
        $response->getBody()->write(json_encode($result['data']));
        return $response;
    }

    public function getDoctorsBySpecialty(Request $request, Response $response, $args)
    {
        $specialtyId = $args['specialtyId'];
        $doctors = $this->doctorService->getDoctorsBySpecialty($specialtyId);

        if (empty($doctors)) {
            $error = ['error' => 'No se econtraron doctores'];
            $response->getBody()->write(json_encode($error));
            return $response;
        }

        $response->getBody()->write(json_encode($doctors));
        return $response;
    }
}