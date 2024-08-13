<?php
namespace App\Controllers;

use App\Services\AppointmentService;
use App\Services\PatientService;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AppointmentController
{
    private $app;
    private $appointmentService;

    public function __construct($app, $entityManager)
    {
        $this->app = $app;
        $this->appointmentService = new AppointmentService($entityManager);
        $this->patientService = new PatientService($entityManager);
    }

    public function buildRoutes()
    {
        $this->app->post('/appointments', [$this, 'create']);
        $this->app->get('/appointments/{dni}', [$this, 'getAppointmentsByDni']);
        $this->app->delete('/appointments/{appointmentId}', [$this, 'delete']);
        $this->app->put('/appointments/{appointmentId}', [$this, 'update']);
        }

    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $doctorId = $data['doctor_id'];
        $appointmentDate = $data['appointment_date'];
        $patientData = $data['patient'];
    
        $patient = $this->patientService->create($patientData);
    
        if ($patient) {
            $appointment = $this->appointmentService->create($doctorId, $patient->getId(), $appointmentDate);
    
            $response->getBody()->write(json_encode($appointment));
            return $response;
        }
    
        $error = ['error' => 'Error al crear el turno o el paciente.'];
        $response->getBody()->write(json_encode($error));
        return $response;
    }
    


    public function getAppointmentsByDni(Request $request, Response $response, $args): Response{
        $dni = $args['dni'];

        $appoinmentsList = json_encode($this->appointmentService->getAppointmentsByDni($dni));
        $response->getBody()->write($appoinmentsList);
        return $response;
    }


    public function delete(Request $request, Response $response, $args)
    {
        $appointmentId = $args['appointmentId'];
        $appointment = json_encode($this->appointmentService->delete($appointmentId));
        $response->getBody()->write($appointment);
        return $response;
    }

    public function update(Request $request, Response $response, $args)
    {
        $appointment = json_decode($request->getBody(), true);
        $appointmentId = $args['appointmentId'];
        $appointment = json_encode($this->appointmentService->update($appointmentId, $appointment));
        $response->getBody()->write($appointment);
        return $response;
    }
}
