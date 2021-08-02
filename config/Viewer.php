<?php

namespace config;

/**
 * Class Viewer
 * @package config
 * @author Christian Felix
 */
class Viewer
{
    public $viewData = [];

    protected $mainPage = 'main.phtml';
    protected $naviPage = 'navi.phtml';

    /**
     * Viewer constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        if (!file_exists('public/' . $this->mainPage)) {
            throw new \Exception('File : ' . $this->mainPage . 'does not exit!');
        }
    }

    /**
     * @param array $viewData
     * @throws \Exception
     */
    public function setData(array $viewData)
    {
        $this->viewData = $viewData; 

        if (array_key_exists('navi',$viewData)) {

            $this->naviPage = 'src/Templates/' . $viewData['navi'];


            if (!file_exists(  $this->naviPage)) {
                throw new \Exception('File : ' . $this->naviPage . ' does not exist');
            }
        }
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

        if (in_array('username', $this->viewData)) { 
            $content = str_replace('{username}', $this->viewData['username'], $content);
        }    
            
        // body content
        $content = str_replace('{body}', $body, $content);
        // navi content

        if ($this->naviPage) {
            $content = str_replace('{navi}', $this->renderNavi($this->naviPage), $content);
        }        

        return $content;
    }

    protected function renderNavi(string $file)
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