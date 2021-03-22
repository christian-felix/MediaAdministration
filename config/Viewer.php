<?php

namespace config;

/**
 * Class Viewer
 */
class Viewer
{
    public $viewData = [];

    public function __construct()
    {

    }

    /**
     * @param array $viewData
     */
    public function setData(array $viewData)
    {
        $this->viewData = $viewData;
    }

    /**
     * @param $file
     * @return false|string|string[]
     */
    public function render($file)
    {
        return $this->renderBody($file);
    }

    /**
     * @param $body
     * @return false|string|string[]
     */
    protected function renderMain($body)
    {
        ob_start();
        include_once('public/main.phtml');
        $content = ob_get_clean();
        $content = str_replace('{username}', $this->viewData['username'], $content);
        $content = str_replace('{body}', $body, $content);

        return $content;
    }

    /**
     * @param $file
     * @return false|string|string[]
     */
    protected function renderBody($file)
    {
        ob_start();
        include_once($file);
        $body =  ob_get_clean();

        return $this->renderMain($body);
    }
}