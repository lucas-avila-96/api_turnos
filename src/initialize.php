<?php
require_once "bootstrap.php";

use App\Models\Specialty;
use App\Models\Doctor;

if (!isset($entityManager)) {
    echo "Entity manager is not set.\n";
    return;
}

$specialtiesData = json_decode(file_get_contents(__DIR__ . '/Models/especialidades.json'), true);

foreach ($specialtiesData as $specialtyData) {
    $specialty = new Specialty($specialtyData);
    $entityManager->persist($specialty);
}
$entityManager->flush();

$specialtyRepository = $entityManager->getRepository(Specialty::class);

$doctorsData = json_decode(file_get_contents(__DIR__ . '/Models/doctores.json'), true);

foreach ($doctorsData as $doctorData) {
    $specialty = $specialtyRepository->find($doctorData['specialty']);
    if ($specialty) {
        $doctor = new Doctor($doctorData);
        $doctor->setSpecialty($specialty);
        $entityManager->persist($doctor);
    } else {
        echo "Especialidad no encontrada ";
    }
}
$entityManager->flush();
