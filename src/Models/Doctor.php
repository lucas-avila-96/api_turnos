<?php
namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity
 * @ORM\Table(name="doctor")
 */
class Doctor implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string")
     */
    private $lastName;

    /**
     * @ORM\ManyToOne(targetEntity="Specialty", inversedBy="doctors")
     * @ORM\JoinColumn(name="specialty_id", referencedColumnName="id")
     */
    private $specialty;

    public function __construct($data = ["firstName" => "", "lastName" => ""])
    {
        $this->setFirstName($data["firstName"]);
        $this->setLastName($data["lastName"]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getSpecialty()
    {
        return $this->specialty;
    }

    public function setFirstName($firstName)
    {
        if (isset($firstName) && is_string($firstName) && strlen($firstName) > 0) {
            $this->firstName = $firstName;
        }
    }

    public function setLastName($lastName)
    {
        if (isset($lastName) && is_string($lastName) && strlen($lastName) > 0) {
            $this->lastName = $lastName;
        }
    }

    public function setSpecialty($specialty)
    {
        $this->specialty = $specialty;
		$specialty->addDoctor($this);
    }

    public function jsonSerialize()
    {
        return [
            "id" => $this->id,
            "firstName" => $this->firstName,
            "lastName" => $this->lastName,
            "specialty" => $this->specialty ? $this->specialty->jsonSerialize() : null
        ];
    }
}
