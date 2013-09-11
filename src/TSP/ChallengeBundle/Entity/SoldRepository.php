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

class BuyRepository extends EntityRepository {

    public function findAllByDateRange($dateIni, $dateEnd){
        $em = $this->getEntityManager();

        $dql = 'SELECT p.description as product,
                       p.cost as cost,
                       p.price as price,
                       sum(s.units) as units
                FROM ChallengeBundle:Sold s JOIN b.product p
                WHERE s.date between :fechaIni AND :fechaFin
                GROUP BY s.product';

        $consulta = $em->createQuery($dql);
        $consulta->setParameter('fechaIni', $dateIni);
        $consulta->setParameter('fechaFin', $dateEnd);
        $consulta->setMaxResults(10);

        return $consulta->getResult();
    }


    public function findByCountry($country){

    }
}