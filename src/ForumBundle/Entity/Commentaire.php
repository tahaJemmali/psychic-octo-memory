<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LoginBundle\Entity\User;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire")
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\CommentaireRepository")
 */
class Commentaire
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
     * @ORM\ManyToOne(targetEntity="ForumBundle\Entity\Sujet",inversedBy="commentaires")
     */
    private $sujet;

    /**
     * Get sujet
     *
     * @return sujet
     */
    public function getSujet()
    {
        return $this->sujet;
    }
    /**
     * Set projet
     *
     * @param $sujet
     * @return Commentaire
     */
    public function setSujet($sujet)
    {
        $this->sujet = $sujet;

        return $this;
    }

    /**
     * @ORM\ManyToOne(targetEntity="LoginBundle\Entity\User",inversedBy="commentaires")
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
     * @return Commentaire
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
     * Set text
     *
     * @param string $text
     *
     * @return Commentaire
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
     * @return Commentaire
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
}

