<?php


namespace App\Services;

use App\Models\Doctor;
use App\Models\Specialty;

class DoctorService
{
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create($doctorData = [
        "name" => "",
        "email" => "",
        "specialtyId" => ""
    ])
    {
        // Verifica si la especialidad existe
        $specialty = $this->entityManager->getRepository(Specialty::class)->find($doctorData['specialtyId']);
        if (!$specialty) {
            throw new \Exception('Specialty not found.');
        }

        $doctor = new Doctor();
        $doctor->setName($doctorData['name']);
        $doctor->setEmail($doctorData['email']);
        $doctor->setSpecialty($specialty);

        $this->entityManager->persist($doctor);
        $this->entityManager->flush();

        return $doctor;
    }

    public function update($doctorId, $doctorData = [
        "name" => "",
        "email" => "",
        "specialtyId" => ""
    ])
    {
        $doctor = $this->getDoctor($doctorId);
        if (!$doctor) {
            throw new \Exception('Doctor not found.');
        }

        if (isset($doctorData['name'])) {
            $doctor->setName($doctorData['name']);
        }
        if (isset($doctorData['email'])) {
            $doctor->setEmail($doctorData['email']);
        }
        if (isset($doctorData['specialtyId'])) {
            $specialty = $this->entityManager->getRepository(Specialty::class)->find($doctorData['specialtyId']);
            if (!$specialty) {
                throw new \Exception('Specialty not found.');
            }
            $doctor->setSpecialty($specialty);
        }

        $this->entityManager->persist($doctor);
        $this->entityManager->flush();

        return $doctor;
    }

    public function delete($doctorId)
    {
        $doctor = $this->getDoctor($doctorId);
        if (!$doctor) {
            throw new \Exception('Doctor not found.');
        }

        $this->entityManager->remove($doctor);
        $this->entityManager->flush();

        return [
            'success' => true,
            'message' => 'Doctor deleted successfully',
            'data' => ['id' => $doctorId]
        ];
    }

    public function getDoctor($id)
    {
        $doctorRepository = $this->entityManager->getRepository(Doctor::class);
        return $doctorRepository->find($id);
    }

    public function getDoctors()
    {
        $doctorRepository = $this->entityManager->getRepository(Doctor::class);
        return $doctorRepository->findAll();
    }

    public function getDoctorsBySpecialty($specialtyId)
    {
        $specialty = $this->entityManager->getRepository(Specialty::class)->find($specialtyId);
        if (!$specialty) {
            throw new \Exception('Specialty not found.');
        }

        $doctorRepository = $this->entityManager->getRepository(Doctor::class);
        return $doctorRepository->findBy(['specialty' => $specialty]);
    }
}
