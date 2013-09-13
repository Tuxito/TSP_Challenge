<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alfredo
 * Date: 5/09/13
 * Time: 19:02
 * To change this template use File | Settings | File Templates.
 */

namespace TSP\ChallengeBundle\Util;


class Util {

    /**
     * Function to invoke the repository query for sales
     * @param $idCountry
     * @param $em
     * @param $startDate
     * @param $endDate
     * @param $firstResult
     * @param $maxResults
     * @param $orderField
     * @param $order
     * @return array
     */
    static public function getProductList($idCountry, $em, $startDate, $endDate,
                                          $firstResult,$maxResults,$orderField,$order){


        // list of products
        $total = 0;
        if ($idCountry == 0){
            // General
            $total = $em->getRepository('ChallengeBundle:Sale')->countAllByDateRange($startDate,$endDate);
            $results = $em->getRepository('ChallengeBundle:Sale')->findAllByDateRange($startDate,$endDate,
                $firstResult,$maxResults,$orderField,$order);
        } else{
            // By country
            $total = $em->getRepository('ChallengeBundle:Sale')->countAllByCountryAndDateRange($idCountry,$startDate,$endDate);
            $results = $em->getRepository('ChallengeBundle:Sale')->findAllByCountryAndDateRange($idCountry,
                $startDate,$endDate,$firstResult,$maxResults,$orderField,$order);
        }

        return array('totalRecords' => $total, 'results' => $results);
    }

    /**
     * Function to calculate total number of pages
     * @param $total
     * @param $recordsPerPage
     * @return float
     */
    static public function calculatePages($total,$recordsPerPage){
        $resto=$total%$recordsPerPage;
        $paginas = 0;

        if($resto==0) {
            $paginas=$total/$recordsPerPage;
        } else {
            $paginas=(($total-$resto)/$recordsPerPage)+1;
        }
        return $paginas;
    }


    /**
     * Function to parse the date provided by the calendar, from mm/dd/yyyy to yyyy/mm/dd
     * @param $dateFromCalendar
     * @return mixed
     */
    static public function parseDateFromCalendar($dateFromCalendar){

        $dateExploded = explode("/",$dateFromCalendar);
        return ($dateExploded[2].'/'.$dateExploded[0].'/'.$dateExploded[1]);
    }
}