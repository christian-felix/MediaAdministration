<?php

namespace config;

/**
 * Class Router
 * @package config
 * @author Christian Felix
 */
class Router
{
    protected $rootDirectory;
    protected static $Viewer;
    protected $Request;
    protected $Controller;

    /**
     * Router constructor.
     * @param Viewer $viewer
     */
    public function __construct(Viewer $viewer, Request $request)
    {
        $this->rootDirectory = $_SERVER['DOCUMENT_ROOT'];
        self::$Viewer = $viewer;
        $this->Request = $request;
    }

    /**
     * @param string $url
     * @return mixed
     */
    public function handleRequest(string $url)
    {
        $this->Request->buildRequest($url);

        $controllerClass = 'src\Controller\\'.$this->Request->getController();
        $this->Controller = new $controllerClass(self::$Viewer);
        $action = $this->Request->getAction();
        $actionID = $this->Request->getEntityID();

        if (empty($actionID)) {

            return $this->Controller->$action();

        } else {

            return $this->Controller->$action($actionID);
        }
    }
}
