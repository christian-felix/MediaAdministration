<?php


namespace config;

/**
 * Class Request
 * @package config
 */
class Request
{
    protected $controller = null;
    protected $action = null;
    protected $entityID = null;

    //simple pattern definition for request handling
    protected const PATTERN = '/\/(\w+)(\/)?(add|((\w+)(\/+)(\d+)))?/';
    protected static $flags = PREG_SET_ORDER;

    const CONTROLLER = 1;
    const ACTION = 5;
    const ENTITY_ID = 7;

    protected static $routeIndex = [
        'controller' => 1,
        'action'     => 5,
        'entityID'   => 7
    ];

    public function __construct()
    {

    }

    /**
     * @param string $url
     */
    public function buildRequest(string $url)
    {
        $matches = [];

        if (preg_match(self::PATTERN, $url, $matches)){

            switch (count($matches)) {

                case 2:
                    $this->controller = $matches[self::$routeIndex['controller']];
                    $this->action = 'index';
                    break;
                case 4:
                    $this->controller = $matches[self::$routeIndex['controller']];
                    $this->action = $matches[self::$routeIndex['action']-2];
                    break;
                case 8:
                    $this->controller = $matches[self::$routeIndex['controller']];
                    $this->action = $matches[self::$routeIndex['action']];
                    $this->entityID = $matches[self::$routeIndex['entityID']];
                    break;
            }
        } else {

            $this->controller = 'index';
            $this->action = 'index';
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getController()
    {
        if (empty($this->controller)) {
            throw new \Exception('Controller could not be identified by Request ');
        }

        return sprintf('%s%s', ucfirst($this->controller), 'Controller' );
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return int
     */
    public function getEntityID()
    {
        return $this->entityID;
    }
}