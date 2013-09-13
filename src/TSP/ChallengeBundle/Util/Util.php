<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alfredo
 * Date: 5/09/13
 * Time: 19:02
 * To change this template use File | Settings | File Templates.
 */

namespace TSP\ChallengeBundle\Util;

use TSP\ChallengeBundle\Util\GridParams;

class Util {

    /**
     * Function to init required params for grid
     * @param $container
     * @param $postParameters
     * @return array
     */
    static public function initParams($container,$postParameters){

        // get the country via postParameters
        $idCountry = $container->getParameter('tsp.default_country');
        if (isset($postParameters['idCountry'])){
            $idCountry = $postParameters['idCountry'];
        } else if (isset($postParameters['selectedCountry'])){
            $idCountry = $postParameters['selectedCountry'];
        }

        // get dates. If not range is set, today by default
        $startDate = new \DateTime('today');
        $endDate = new \DateTime('today');

        if (isset($postParameters['endDate']) && isset($postParameters['startDate'])){
            $fechaIni = $postParameters['startDate'];
            $fechaFin = $postParameters['endDate'];

            $startDate = new \DateTime(Util::parseDateFromCalendar($fechaIni));
            $endDate = new \DateTime(Util::parseDateFromCalendar($fechaFin));
        }


        // GridParams
        $pageSize = $container->getParameter('tsp.default_max_records');

        // get default max records per page
        if (isset($postParameters['pagesize'])){
            $pageSize = $postParameters['pagesize'];
        }

        // params for grid sorting
        $orderField =$container->getParameter('tsp.default_order_field');
        $order = $container->getParameter('tsp.default_order');

        if (isset($postParameters['orderField'])){
            $orderField = $postParameters['orderField'];
            $order = $postParameters['order'];
        }

        // next page and first record for pagination
        $nextPage = 1;
        $firstRecord = 0;

        if (isset($_POST['nextPage'])){
            $nextPage = $_POST['nextPage'];
            $firstRecord = ($nextPage - 1) *  $pageSize;
        }

        // return params
        return array('country' => $idCountry,
                     'startDate' => $startDate,
                     'endDate' => $endDate,
                     '$pageSize' => $pageSize,
                     'orderField' => $orderField,
                     'order' => $order,
                     'nextPage' => $nextPage,
                     'firstRecord' => $firstRecord );
    }


    /**
     * Function to invoke the repository query for sales
     * @param $idCountry
     * @param $em
     * @param $startDate
     * @param $endDate
     * @param $firstResult
     * @param $gridParams
     * @return array
     */
    static public function getProductList($idCountry, $em, $startDate, $endDate,
                                          $firstResult,$gridParams){

        // list of products
        $total = 0;
        if ($idCountry == 0){
            // General
            $total = $em->getRepository('ChallengeBundle:Sale')->countAllByDateRange($startDate,$endDate);
            $results = $em->getRepository('ChallengeBundle:Sale')->findAllByDateRange($startDate,$endDate,
                $firstResult,$gridParams->getMaxResultsPerPage(),$gridParams->getOrderField(),$gridParams->getOrder());
        } else{
            // By country
            $total = $em->getRepository('ChallengeBundle:Sale')->countAllByCountryAndDateRange($idCountry,$startDate,$endDate);
            $results = $em->getRepository('ChallengeBundle:Sale')->findAllByCountryAndDateRange($idCountry,
                $startDate,$endDate,$firstResult,$gridParams->getMaxResultsPerPage(),$gridParams->getOrderField(),$gridParams->getOrder());
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


    /**
     * Function to generate an array of accesible properties for an object
     * @param $object
     * @return array
     */
    static public function dismount($object) {
        $reflectionClass = new \ReflectionClass(get_class($object));
        $array = array();
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($object);
            $property->setAccessible(false);
        }
        return $array;
    }
}