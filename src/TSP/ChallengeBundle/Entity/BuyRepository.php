<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alfredo
 * Date: 4/09/13
 * Time: 12:15
 * To change this template use File | Settings | File Templates.
 */

namespace TSP\ChallengeBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BuyRepository extends EntityRepository {

    /**
     * Function to count the total of records between 2 dates for general query
     */
    public function countAllByDateRange($dateIni, $dateEnd){
        $em = $this->getEntityManager();

        $dql = 'SELECT p.description as product,
                       p.cost as cost,
                       p.price as price,
                       sum(b.units) as units
                FROM ChallengeBundle:Buy b JOIN b.product p
                WHERE b.date between :fechaIni AND :fechaFin
                GROUP BY b.product';

        $consulta = $em->createQuery($dql);
        $consulta->setParameter('fechaIni', $dateIni);
        $consulta->setParameter('fechaFin', $dateEnd);

        return $consulta->getResult();
    }

    /**
     * Function to get the list of product for the selected page between 2 dates
     */
    public function findAllByDateRange($dateIni, $dateEnd,$firstResult,$maxResults){
        $em = $this->getEntityManager();

        $dql = 'SELECT p.description as product,
                       p.cost as cost,
                       p.price as price,
                       sum(b.units) as units
                FROM ChallengeBundle:Buy b JOIN b.product p
                WHERE b.date between :fechaIni AND :fechaFin
                GROUP BY b.product';

        $consulta = $em->createQuery($dql);
        $consulta->setParameter('fechaIni', $dateIni);
        $consulta->setParameter('fechaFin', $dateEnd);
        $consulta->setFirstResult($firstResult);
        $consulta->setMaxResults($maxResults);

        return $consulta->getResult();
    }

    /**
     * Function to count the total of records between 2 dates for per market query
     */
    public function countAllByCountryAndDateRange($idCountry,$dateIni, $dateEnd){
        $em = $this->getEntityManager();

        $dql = 'SELECT p.description as product,
                       p.cost as cost,
                       p.price as price,
                       sum(b.units) as units
                FROM ChallengeBundle:Buy b JOIN b.product p
                WHERE b.date between :fechaIni AND :fechaFin AND b.country = :country
                GROUP BY b.product';

        $consulta = $em->createQuery($dql);
        $consulta->setParameter('fechaIni', $dateIni);
        $consulta->setParameter('fechaFin', $dateEnd);
        $consulta->setParameter('country', $idCountry);

        return $consulta->getResult();
    }

    /**
     * Function to get the list of product for the selected page between 2 dates and country
     */
    public function findAllByCountryAndDateRange($idCountry,$dateIni, $dateEnd,$firstResult,$maxResults){
        $em = $this->getEntityManager();

        $dql = 'SELECT p.description as product,
                       p.cost as cost,
                       p.price as price,
                       sum(b.units) as units
                FROM ChallengeBundle:Buy b JOIN b.product p
                WHERE b.date between :fechaIni AND :fechaFin AND b.country = :country
                GROUP BY b.product';

        $consulta = $em->createQuery($dql);
        $consulta->setParameter('fechaIni', $dateIni);
        $consulta->setParameter('fechaFin', $dateEnd);
        $consulta->setParameter('country', $idCountry);
        $consulta->setFirstResult($firstResult);
        $consulta->setMaxResults($maxResults);

        return $consulta->getResult();
    }


    public function findByCountry($country){

    }
}