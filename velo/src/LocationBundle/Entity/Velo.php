<?php

namespace LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Velo
 *
 * @ORM\Table(name="velo")
 * @ORM\Entity(repositoryClass="LocationBundle\Repository\VeloRepository")
 */
class Velo
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
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
 * @var string
 *
 * @ORM\Column(name="description", type="string", length=255)
 */
    private $description;

    /**
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param string $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }

    /**
 * @var string
 *
 * @ORM\Column(name="etat", type="string", length=255)
 */
    private $etat;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", length=255)
     */
    private $prix;

    /**
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @param float $prix
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateMaintenance", type="date")
     */
    private $dateMaintenance;

    /**
     * @var int
     *
     * @ORM\Column(name="cycleMaintenance", type="integer")
     */
    private $cycleMaintenance;


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
     * Set type
     *
     * @param string $type
     *
     * @return Velo
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
     * Set dateMaintenance
     *
     * @param \DateTime $dateMaintenance
     *
     * @return Velo
     */
    public function setDateMaintenance($dateMaintenance)
    {
        $this->dateMaintenance = $dateMaintenance;

        return $this;
    }

    /**
     * Get dateMaintenance
     *
     * @return \DateTime
     */
    public function getDateMaintenance()
    {
        return $this->dateMaintenance;
    }

    /**
     * Set cycleMaintenance
     *
     * @param integer $cycleMaintenance
     *
     * @return Velo
     */
    public function setCycleMaintenance($cycleMaintenance)
    {
        $this->cycleMaintenance = $cycleMaintenance;

        return $this;
    }

    /**
     * Get cycleMaintenance
     *
     * @return int
     */
    public function getCycleMaintenance()
    {
        return $this->cycleMaintenance;
    }
}

