<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alfredo
 * Date: 3/09/13
 * Time: 9:26
 * To change this template use File | Settings | File Templates.
 */

namespace TSP\ChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="TSP\ChallengeBundle\Entity\BuyRepository")
 */

class Buy {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="TSP\ChallengeBundle\Entity\Country")
     *  @ORM\JoinColumn(name="country_fk", referencedColumnName="id")
     */
    protected $country;

    /**
     * @ORM\ManyToOne(targetEntity="TSP\ChallengeBundle\Entity\Product")
     * @ORM\JoinColumn(name="product_fk", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\Column(type="integer")
     */
    protected $units;

    /**
     * @ORM\Column(type="date")
     */
    protected $date;

    /**
     * @param mixed $country
     */
    public function setCountry(\TSP\ChallengeBundle\Entity\Country $country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $units
     */
    public function setUnits($units)
    {
        $this->units = $units;
    }

    /**
     * @return mixed
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * @param mixed $product
     */
    public function setProduct(\TSP\ChallengeBundle\Entity\Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }
}