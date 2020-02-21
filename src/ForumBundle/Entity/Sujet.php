<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LoginBundle\Entity\User;

/**
 * Sujet
 *
 * @ORM\Table(name="sujet")
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\SujetRepository")
 */
class Sujet
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $dateCreation;

    /**
     * @var int
     *
     * @ORM\Column(name="commntaire_on", type="boolean")
     */
    private $commntaireOn;


    /**
     * @ORM\OneToMany(targetEntity="ForumBundle\Entity\Commentaire",mappedBy="sujet", cascade={"persist", "remove"})
     */
    private $commentaires;

    /**
     * Get commentaires
     *
     * @return Commentaire
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * Set commentaires
     *
     * @param $commentaires
     *
     * @return Sujet
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;

        return $this;
    }

    /**
     * @ORM\ManyToOne(targetEntity="LoginBundle\Entity\User",inversedBy="sujets")
     */
    private $user;

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param $user
     * @return Sujet
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

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
     * Set titre
     *
     * @param string $titre
     *
     * @return Sujet
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Sujet
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Sujet
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set commntaireOn
     *
     * @param integer $commntaireOn
     *
     * @return Sujet
     */
    public function setCommntaireOn($commntaireOn)
    {
        $this->commntaireOn = $commntaireOn;

        return $this;
    }

    /**
     * Get commntaireOn
     *
     * @return int
     */
    public function getCommntaireOn()
    {
        return $this->commntaireOn;
    }
}

