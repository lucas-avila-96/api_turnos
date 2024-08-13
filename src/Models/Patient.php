<?php
namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity
 * @ORM\Table(name="patient")
 */
class Patient implements JsonSerializable
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
     * @ORM\Column(type="string", unique=true)
     */
    private $dni;

    public function __construct($data = ["firstName" => "", "lastName" => "", "dni" => ""])
    {
        $this->setFirstName($data["firstName"]);
        $this->setLastName($data["lastName"]);
        $this->setDni($data["dni"]);
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

    public function getDni()
    {
        return $this->dni;
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

    public function setDni($dni)
    {
        if (isset($dni) && is_string($dni) && strlen($dni) > 0) {
            $this->dni = $dni;
        }
    }

    public function getFullName()
    {
        return strtoupper($this->lastName) . ", " . $this->firstName;
    }

    public function jsonSerialize()
    {
        return [
            "id" => $this->id,
            "firstName" => $this->firstName,
            "lastName" => $this->lastName,
            "dni" => $this->dni,
        ];
    }
}
