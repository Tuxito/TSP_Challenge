<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alfredo
 * Date: 3/09/13
 * Time: 9:13
 * To change this template use File | Settings | File Templates.
 */

namespace TSP\ChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Country {

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

    public function __toString()
    {
        return $this->getDescription();
    }
}