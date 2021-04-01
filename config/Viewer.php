<?php

namespace config;

/**
 * Class Viewer
 * @package config
 */
class Viewer
{
    public $viewData = [];

    protected $mainPage = 'main.phtml';

    protected $naviPage = 'navi.phtml';

    /**
     * Viewer constructor.
     * @throws FileNotFoundException
     */
    public function __construct()
    {
        if (!file_exists('public/' . $this->mainPage)) {
            throw new FileNotFoundException('File : ' . $this->mainPage );
        }
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

        include_once('public/' . $this->mainPage);
        $content = ob_get_clean();
        $content = str_replace('{username}', $this->viewData['username'], $content);
        $content = str_replace('{body}', $body, $content);

        //navigation
        $content = str_replace('{navi}', $this->renderNavi(), $content);

        return $content;
    }

    protected function renderNavi($file = 'src/Templates/media/navi.phtml')
    {
        ob_start();
        include_once($file);

        return ob_get_clean();
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