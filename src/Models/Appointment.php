<?php
namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="appointments")
 */
class Appointment implements \JsonSerializable
{
    /** 
     * @ORM\Id 
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue 
     */
    private $id;

    /** 
     * @ORM\Column(type="datetime") 
     */
    private $appointmentDate;

    /** 
     * @ORM\ManyToOne(targetEntity="Doctor") 
     * @ORM\JoinColumn(name="doctor_id", referencedColumnName="id", onDelete="CASCADE") 
     */
    private $doctor;

    /** 
     * @ORM\ManyToOne(targetEntity="Patient") 
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id", onDelete="CASCADE") 
     */
    private $patient;

    public function setDoctor($doctor)
    {
        $this->doctor = $doctor;
    }

    public function setPatient($patient)
    {
        $this->patient = $patient;
    }

    public function setAppointmentDate($appointmentDate)
    {
        $this->appointmentDate = $appointmentDate;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'appointmentDate' => $this->appointmentDate->format('Y-m-d H:i:s'),
            'doctor' => $this->doctor,
            'patient' => $this->patient,
        ];
    }
}
