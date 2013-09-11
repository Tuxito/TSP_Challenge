<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alfredo
 * Date: 3/09/13
 * Time: 9:24
 * To change this template use File | Settings | File Templates.
 */

namespace TSP\ChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Product {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $description;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    protected $cost;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    protected $price;

    /**
     * @param mixed $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * @return mixed
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }




    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
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


    public function __toString()
    {
        return $this->getDescription();
    }
}