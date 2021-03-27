<?php


namespace src\Controller;


use src\Service\Paginator;

/**
 * Class PaginatorController
 * @package src\Controller
 */
class PaginatorController
{
    protected $Paginator;

    public function __construct()
    {
        $this->Paginator = new Paginator();
    }

    /**
     * @param int $page
     */
    public function next(int $page)
    {

    }

    /**
     * @param int $page
     */
    public function prev(int $page)
    {

    }

    public function jumpTo(int $page)
    {


    }

}