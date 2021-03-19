<?php

namespace config;

/**
 * Class Viewer
 */
class Viewer
{
    public $viewData;

    public function __construct()
    {

    }

    public function setData(array $viewData)
    {
        $this->viewData = $viewData;
    }

    public function renderView($file)
    {
        ob_start();
        include_once($file);

        $data =  ob_get_clean();

        $data = str_replace('{username}', 'TEST--TEST', $data);

        return $data;
    }
}