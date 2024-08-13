<?php
namespace App\Services;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use Doctrine\ORM\EntityManager;

class AppointmentService
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create($doctorId, $patientId, $appointmentDate)
    {
        $doctor = $this->entityManager->getRepository(Doctor::class)->find($doctorId);
        $patient = $this->entityManager->getRepository(Patient::class)->find($patientId);

        if (!$doctor || !$patient) {
            throw new \Exception('Doctor or Patient not found.');
        }

        $appointment = new Appointment();
        $appointment->setDoctor($doctor);
        $appointment->setPatient($patient);
        $appointment->setAppointmentDate(new \DateTime($appointmentDate));

        $this->entityManager->persist($appointment);
        $this->entityManager->flush();

        return $appointment;
    }

    public function getAppointmentsById($id){
        {
            $appointment = $this->entityManager->getRepository(Appointment::class)->find($id);
            if (!$appointment) {
                throw new \Exception('Turno no encontrado');
            }
            return $appointment;
        }
    }
    

    public function getAppointmentsByDni($dni){
        {
            $patient = $this->entityManager->getRepository(Patient::class)->findOneBy(['dni'=> $dni]);
            if (!$patient) {
                throw new \Exception('Patient not found.');
            }
    
            $appointmentRepository = $this->entityManager->getRepository(Appointment::class);
            return $appointmentRepository->findBy(['patient' => $patient]);
        }
    }

    public function update($appointmentId, $appointmentData = [
        "appointmentDate" => "",
        "appointmentId" => ""
    ])
    {
        $appointment = $this->getAppointmentsById($appointmentId);
        if (!$appointment) {
            throw new \Exception('turno no encontrado');
        }

        if (isset($appointmentData['appointmentDate'])) {
            $date = new \DateTime($appointmentData['appointmentDate']);
            $appointment->setAppointmentDate($date);
        }

        $this->entityManager->persist($appointment);
        $this->entityManager->flush();

        return $appointment;
    }

    public function delete($appointmentId)
    {
        $appointment = $this->getAppointmentsById($appointmentId);

        $this->entityManager->remove($appointment);
        $this->entityManager->flush();

        return [
            'success' => true,
            'message' => 'Turno eliminado',
            'data' => ['id' => $appointmentId]
        ];
    }
}
