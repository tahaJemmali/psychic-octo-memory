<?php

namespace ECommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cart
 *
 * @ORM\Table(name="cart")
 * @ORM\Entity(repositoryClass="ECommerceBundle\Repository\CartRepository")
 */
class Cart
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
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;


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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Cart
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @ORM\ManyToOne(targetEntity="\ECommerceBundle\Entity\Product", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */

    public function setProduct($product)
    {
        $this->product = $product;
    }


    /**
     * @ORM\ManyToOne(targetEntity="\LoginBundle\Entity\User", cascade={"persist","merge"} ,inversedBy="medias", fetch="LAZY" )
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id",nullable=true)
     */
    protected $user;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */

    public function setUser($user)
    {
        $this->user = $user;
    }


    /**
     * @var \DateTime
     * @ORM\Column(name="added_on", type="datetime")
     */
    protected $addedOn;

    public function __construct()
    {
        $this->addedOn= new \DateTime('now');
    }

}

