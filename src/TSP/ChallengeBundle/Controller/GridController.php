<?php

namespace TSP\ChallengeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TSP\ChallengeBundle\Util\Util;

class GridController extends Controller{

    /**
     * Action to paginate the result list
     * @return Response
     */
    public function paginateAction(){

        // country selected (0 for 'General')
        $idCountry = $_POST['selectedCountry'];

        $fechaIni = $_POST['startDate'];
        $fechaFin = $_POST['endDate'];

        $startDate = new \DateTime(Util::parseDateFromCalendar($fechaIni));
        $endDate = new \DateTime(Util::parseDateFromCalendar($fechaFin));

        // get selected pagesize
        $maxResults = $this->container->getParameter('tsp.default_max_records');
        if (isset($_POST['pagesize'])){
            $maxResults = $_POST['pagesize'];
        }

        // page requested and first record to show
        $nextPage = $_POST['nextPage'];
        $firstRecord = ($nextPage - 1) * $maxResults;

        // default sorting
        $orderField = $this->container->getParameter('tsp.default_order_field');
        $order = $this->container->getParameter('tsp.default_order');

        if (isset($_POST['orderField'])){
            $orderField = $_POST['orderField'];
            $order = $_POST['order'];
        }

        // Get the entity manager
        $em = $this->getDoctrine()->getManager();

        // get the product list
        $data = Util::getProductList($idCountry, $em, $startDate, $endDate,$firstRecord,$maxResults,$orderField,$order);

        if (!$data['results']) {
            return $this->render('ChallengeBundle:Default:noProducts.html.twig');
        }

        // calculate total pages and grid data
        $totalPages = Util::calculatePages(count($data['totalRecords']),$maxResults);
        $grid = array('currentPage' => $nextPage,
                      'totalPages' =>  $totalPages,
                      'totalRecords' => count($data['totalRecords']));




        $response = array('gridPaginationData' => $grid,
            'buy' => $data['results'],
            'startDate' => $startDate->format('d/m/Y'),
            'endDate' =>  $endDate->format('d/m/Y'),
            'country' => $idCountry,
            'orderField' => $orderField,
            'order' => $order,
            'gridPaginationData' => $grid);

        return new Response(json_encode($response));

    }

}
