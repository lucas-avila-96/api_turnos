<?php

namespace App\Services;

use App\Models\Specialty;

class SpecialtyService
{
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(
        $data = [
            "name" => "",
            "id" => 0
        ]
    ) {
        $existingSpecialty = $this->entityManager->getRepository(Specialty::class)->find($data["id"]);
        if ($existingSpecialty) {
            return $existingSpecialty;
        }
        $specialty = new Specialty($data);
        $this->entityManager->persist($specialty);
        $this->entityManager->flush();
        return $specialty;
    }

    public function getSpecialties()
    {
        $specialtyRepository = $this->entityManager->getRepository(Specialty::class);
        $specialties = $specialtyRepository->findAll();
        return $specialties;
    }

    public function getSpecialty($id)
    {
        $specialtyRepository = $this->entityManager->getRepository(Specialty::class);
        $specialty = $specialtyRepository->find($id);
        return $specialty;
    }

    public function updateSpecialty($id, $data)
    {
        $specialty = $this->getSpecialty($id);
        if (!$specialty) {
            return [
                'success' => false,
                'message' => 'Specialty not found.'
            ];
        }

        $specialty->setName($data['name'] ?? $specialty->getName());

        $this->entityManager->flush();

        return [
            'success' => true,
            'message' => 'Specialty updated successfully',
            'data' => $specialty
        ];
    }

    public function deleteSpecialty($id)
    {
        $specialty = $this->getSpecialty($id);
        if (!$specialty) {
            return [
                'success' => false,
                'message' => 'Specialty not found.'
            ];
        }

        $this->entityManager->remove($specialty);
        $this->entityManager->flush();

        return [
            'success' => true,
            'message' => 'Specialty deleted successfully',
            'data' => ['id' => $id]
        ];
    }

}

