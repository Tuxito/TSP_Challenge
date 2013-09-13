<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alfredo
 * Date: 13/09/13
 * Time: 12:19
 * To change this template use File | Settings | File Templates.
 */

namespace TSP\ChallengeBundle\Util;


class GridParams {

    protected $maxResultsPerPage;

    protected $orderField;

    protected $order;

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $orderField
     */
    public function setOrderField($orderField)
    {
        $this->orderField = $orderField;
    }

    /**
     * @return mixed
     */
    public function getOrderField()
    {
        return $this->orderField;
    }


    /**
     * @param mixed $maxResultsPerPage
     */
    public function setMaxResultsPerPage($maxResultsPerPage)
    {
        $this->maxResultsPerPage = $maxResultsPerPage;
    }

    /**
     * @return mixed
     */
    public function getMaxResultsPerPage()
    {
        return $this->maxResultsPerPage;
    }


}