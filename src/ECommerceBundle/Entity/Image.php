<?php

namespace ECommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="ECommerceBundle\Repository\ImageRepository")
 */
class Image
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
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="ordrer", type="string", length=255)
     */
    private $ordrer;


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
     * Set image
     *
     * @param string $image
     *
     * @return Image
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set ordrer
     *
     * @param string $ordrer
     *
     * @return Image
     */
    public function setOrdrer($ordrer)
    {
        $this->ordrer = $ordrer;

        return $this;
    }

    /**
     * Get ordrer
     *
     * @return string
     */
    public function getOrdrer()
    {
        return $this->ordrer;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product",referencedColumnName="id")
     */
    private $product;

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

}

