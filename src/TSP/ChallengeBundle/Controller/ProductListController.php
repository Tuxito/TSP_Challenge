<?php

namespace TSP\ChallengeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use TSP\ChallengeBundle\Util\Util;
use TSP\ChallengeBundle\Util\GridParams;

class ProductListController extends Controller{

    /**
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function productListAction(){

        // init required params
        $params = Util::initParams($this->container,$_POST);

        // params for sorting and pagesize
        $gridParams =  new GridParams;
        $gridParams->setMaxResultsPerPage($params['$pageSize']);
        $gridParams->setOrderField($params['orderField']);
        $gridParams->setOrder($params['order']);

        // Get the entity manager
        $em = $this->getDoctrine()->getManager();

        // get the product list
        $data = Util::getProductList($params['country'], $em,$params['startDate'],$params['endDate'],
            $params['firstRecord'],
            $gridParams);


        if (!$data['results']) {
            if (!$this->getRequest()->isXmlHttpRequest()){
                return $this->render('ChallengeBundle:Default:noProducts.html.twig');
            }

        }


        $totalPages = Util::calculatePages(count($data['totalRecords']), $gridParams->getMaxResultsPerPage());
        $gridParams->setTotalPages($totalPages);
        $gridParams->setCurrentPage($params['nextPage']);
        $gridParams->setTotalRecords(count($data['totalRecords']));

        // Check if is an AJAX call
        if ($this->getRequest()->isXmlHttpRequest()){
            $response = array('buy' => $data['results'],
                'startDate' => $params['startDate']->format('d/m/Y'),
                'endDate' =>  $params['endDate']->format('d/m/Y'),
                'country' => $params['country'],
                'gridParams' => Util::dismount($gridParams));

            return new Response(json_encode($response));
        } else{
            // Find all countries for the select
            $countries = $em->getRepository('ChallengeBundle:Country')->findAll();

            return $this->render('ChallengeBundle:Default:productList.html.twig',array(
                'buys' => $data['results'],
                'countries' => $countries,
                'selectedCountry' => $params['country'],
                'startDate' => $params['startDate'],
                'endDate' => $params['endDate'],
                'gridParams' => Util::dismount($gridParams)
            ));
        }


    }

}
