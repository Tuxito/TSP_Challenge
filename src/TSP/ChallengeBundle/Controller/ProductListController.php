<?php

namespace TSP\ChallengeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use TSP\ChallengeBundle\Util\Util;

class ProductListController extends Controller{

    /**
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function productListAction(){

        // get selected country. If not set (first time page load), take default country from parameters
        $idCountry = $this->container->getParameter('tsp.default_country');
        if (isset($_POST['idCountry'])){
            $idCountry = $_POST['idCountry'];
        }

        // get dates. If not range is set, today by default
        $startDate = new \DateTime('today');
        $endDate = new \DateTime('today');

        $fechaIni = 'not set';
        $fechaFin = 'not set';

        if (isset($_POST['endDate']) && isset($_POST['startDate'])){
            $fechaIni = $_POST['startDate'];
            $fechaFin = $_POST['endDate'];

            $startDate = new \DateTime(Util::parseDateFromCalendar($fechaIni));
            $endDate = new \DateTime(Util::parseDateFromCalendar($fechaFin));
        }

        // get default max records per page
        $maxResultsPerPage = $this->container->getParameter('tsp.default_max_records');

        // params for default sorting
        $orderField = $this->container->getParameter('tsp.default_order_field');
        $order = $this->container->getParameter('tsp.default_order');

        if (isset($_POST['orderField'])){
            $orderField = $_POST['orderField'];
            $order = $_POST['order'];
        }


        // Get the entity manager
        $em = $this->getDoctrine()->getManager();

        // Find all countries for the select
        $countries = $em->getRepository('ChallengeBundle:Country')->findAll();

        // get the product list
        $data = Util::getProductList($idCountry, $em, $startDate, $endDate,0,$maxResultsPerPage,$orderField,$order);

        if (!$data['results']) {
            return $this->render('ChallengeBundle:Default:noProducts.html.twig');
        }

        $totalPages = Util::calculatePages(count($data['totalRecords']),$maxResultsPerPage);
        $grid = array('currentPage' => 1,
                      'totalPages' =>  $totalPages,
                      'totalRecords' => count($data['totalRecords']));

        return $this->render('ChallengeBundle:Default:productList.html.twig',array(
            'buys' => $data['results'],
            'countries' => $countries,
            'selectedCountry' => $idCountry,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'orderField' => $orderField,
            'order' => $order,
            'gridPaginationData' => $grid
        ));
    }

}
