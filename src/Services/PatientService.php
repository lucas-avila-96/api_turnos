<?php
namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Models\Patient;

class PatientService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create($patientData = [
        "firstName" => "",
        "lastName" => "",
        "dni" => ""
    ])
    {
        $patient = $this->entityManager->getRepository(Patient::class)->findOneBy(['dni' => $patientData['dni']]);
        
        if (!$patient) {
            // Crear paciente si no existe
            $patient = new Patient();
            $patient->setDni($patientData['dni']);
            $patient->setFirstName($patientData['firstName']);
            $patient->setLastName($patientData['lastName']);
            $this->entityManager->persist($patient);
            $this->entityManager->flush();
        }

        return $patient;
    }
}
