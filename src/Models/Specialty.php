<?php
namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity
 * @ORM\Table(name="specialty")
 */
class Specialty implements JsonSerializable
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Doctor", mappedBy="specialty")
     * @var \Doctrine\Common\Collecition\ArrayCollection
     */
    private $doctors;

    public function __construct($data = ["name" => ""])
    {
        $this->setName($data["name"]);
        $this->doctors = new ArrayCollection;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        if (isset($name) && is_string($name) && strlen($name) > 0) {
            $this->name = $name;
        }
    }

    public function getDoctors()
    {
        return $this->doctors;
    }

    public function addDoctor($doctor)
    {
        $this->doctors[] = $doctor;
    }

    public function jsonSerialize()
    {
        return [
            "id" => $this->id,
            "name" => $this->name
        ];
    }
}
