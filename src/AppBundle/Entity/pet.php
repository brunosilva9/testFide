<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * pet
 *
 * @ORM\Table(name="pet")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\petRepository")
 */
class pet
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="chipNumber", type="integer", unique=true)
     */
    private $chipNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=7)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @var bool
     *
     * @ORM\Column(name="sex", type="boolean")
     */
    private $sex;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255)
     */
    private $color;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOfBirth", type="date")
     */
    private $dateOfBirth;

    /**
     * @var bool
     *
     * @ORM\Column(name="neutered", type="boolean")
     */
    private $neutered;

    /**
     * @var string
     *
     * @ORM\Column(name="humanRut", type="string", length=12)
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Owner", inversedBy="pets")
     * @ORM\JoinColumn(name="humanRut", referencedColumnName="rut")
     */
    private $humanRut;

    /**
     * @var string
     * 
     * @ORM\Column(name="observations", type="string", length=255)
     */
    private $observations;



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set chipNumber
     *
     * @param integer $chipNumber
     *
     * @return pet
     */
    public function setChipNumber($chipNumber)
    {
        $this->chipNumber = $chipNumber;

        return $this;
    }

    /**
     * Get chipNumber
     *
     * @return int
     */
    public function getChipNumber()
    {
        return $this->chipNumber;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return pet
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return pet
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return pet
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set sex
     *
     * @param boolean $sex
     *
     * @return pet
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return bool
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return pet
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     *
     * @return pet
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set neutered
     *
     * @param boolean $neutered
     *
     * @return pet
     */
    public function setNeutered($neutered)
    {
        $this->neutered = $neutered;

        return $this;
    }

    /**
     * Get neutered
     *
     * @return bool
     */
    public function getNeutered()
    {
        return $this->neutered;
    }

    /**
     * Set humanRut
     *
     * @param string $humanRut
     *
     * @return pet
     */
    public function setHumanRut($humanRut)
    {
        $this->humanRut = $humanRut;

        return $this;
    }

    /**
     * Get humanRut
     *
     * @return string
     */
    public function getHumanRut()
    {
        return $this->humanRut;
    }

    /**
     * Set observations
     *
     * @param string $observations
     *
     * @return pet
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;

        return $this;
    }

    /**
     * Get observations
     *
     * @return string
     */
    public function getObservations()
    {
        return $this->observations;
    }
}