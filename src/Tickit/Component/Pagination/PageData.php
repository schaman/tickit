<?php

namespace Tickit\Component\Pagination;

/**
 * PageData.
 *
 * Represents a single page of data.
 *
 * @package Tickit\Component\Pagination
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PageData
{
    /**
     * The data represented by this object
     *
     * @var array|\Iterator
     */
    private $data;

    /**
     * The total number of records in the data set.
     *
     * This is not the number of records in this subset of
     * data, but rather the total amount in the superset.
     *
     * @var integer
     */
    private $total;

    /**
     * The page number this data is for
     *
     * @var integer
     */
    private $currentPage;

    /**
     * The total number of pages in the superset of data
     *
     * @var integer
     */
    private $pages;

    /**
     * Constructor.
     *
     * @param array   $data       An array of page data that this object represents
     * @param integer $total      The total number of records in the full data set
     * @param integer $perPage    The number of items per page
     * @param integer $pageNumber The page number this data is for
     */
    public function __construct($data = array(), $total, $perPage, $pageNumber)
    {
        $this->data        = $data;
        $this->total       = (integer) $total;
        $this->currentPage = (integer) $pageNumber;
        $this->pages       = ceil($this->total / $perPage);
    }

    /**
     * Gets the data
     *
     * @return array|\Iterator
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Gets the page number that this data is for
     *
     * @return integer
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * Gets the total number of records in the full data set
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Gets the total pages of data in the superset
     *
     * @return integer
     */
    public function getPages()
    {
        return $this->pages;
    }
}
