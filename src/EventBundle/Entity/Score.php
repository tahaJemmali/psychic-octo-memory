<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Score
 *
 * @ORM\Table(name="score")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\ScoreRepository")
 */
class Score
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
     * @ORM\Column(name="first", type="integer")
     */
    private $first;

    /**
     * @var int
     *
     * @ORM\Column(name="secound", type="integer")
     */
    private $secound;

    /**
     * @var int
     *
     * @ORM\Column(name="third", type="integer")
     */
    private $third;

    /**
     * @ORM\OneToMany(targetEntity="EventBundle\Entity\Evenement",mappedBy="score", cascade={"persist", "remove"})
     */
    private $events;

    /**
     * Get events
     *
     * @return Score
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Set events
     *
     * @param $events
     *
     * @return Score
     */
    public function setEvents($events)
    {
        $this->events = $events;

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
     * Set first
     *
     * @param integer $first
     *
     * @return Score
     */
    public function setFirst($first)
    {
        $this->first = $first;

        return $this;
    }

    /**
     * Get first
     *
     * @return int
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * Set secound
     *
     * @param integer $secound
     *
     * @return Score
     */
    public function setSecound($secound)
    {
        $this->secound = $secound;

        return $this;
    }

    /**
     * Get secound
     *
     * @return int
     */
    public function getSecound()
    {
        return $this->secound;
    }

    /**
     * Set third
     *
     * @param integer $third
     *
     * @return Score
     */
    public function setThird($third)
    {
        $this->third = $third;

        return $this;
    }

    /**
     * Get third
     *
     * @return int
     */
    public function getThird()
    {
        return $this->third;
    }
}

