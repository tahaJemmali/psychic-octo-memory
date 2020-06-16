<?php

namespace ECommerceBundle\Entity;

use LoginBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="ECommerceBundle\Repository\CommandeRepository")
 */
class Commande
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
     * @ORM\Column(name="paiment", type="string", length=255)
     */
    private $paiment;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255)
     */
    private $etat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;


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
     * Set paiment
     *
     * @param string $paiment
     *
     * @return Commande
     */
    public function setPaiment($paiment)
    {
        $this->paiment = $paiment;

        return $this;
    }

    /**
     * Get paiment
     *
     * @return string
     */
    public function getPaiment()
    {
        return $this->paiment;
    }

    /**
     * Set etat
     *
     * @param string $etat
     *
     * @return Commande
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Commande
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Commande
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Many Groups have Many Users.
     * @ORM\ManyToMany(targetEntity="\LoginBundle\Entity\User")
     * @ORM\JoinTable(name="commandes")
     */

    private $commande_user ;

    public function __construct()
    {
        $this->date= new \DateTime('now');
        $this->commande_user = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function addCommandes(User $user)
    {
        if ($this->commande_user->contains($user))
            return;
        $this->commande_user[] = $user;
    }



}

