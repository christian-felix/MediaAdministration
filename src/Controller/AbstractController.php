<?php

namespace src\Controller;

use config\Database;
use config\Viewer;
use src\Service\Paginator;
use src\Service\SearchEngine;

/**
 * Class AbstractController
 * @package src\Controller
 * @author Christian Felix
 */
abstract class AbstractController
{
    /**
     * @var Viewer
     */
    protected Viewer $Viewer;

    /**
     * @var Paginator
     */
    protected Paginator $Paginator;

    /**
     * @var SearchEngine
     */
    protected SearchEngine $SearchEngine;

    /**
     * @var Database
     */
    protected Database $em;

    /**
     * AbstractController constructor.
     * @param Viewer $viewer
     */
    public function __construct(Viewer $viewer)
    {
        $this->Viewer = $viewer;

        $this->em = Database::getInstance();
        $this->Paginator = new Paginator();

        //TODO: inject SearchEngine

    }

    abstract public function add();

    /**
     * @param int $id
     * @return mixed
     */
    abstract public function view(int $id);

    /**
     * @param int $id
     * @return mixed
     */
    abstract public function edit(int $id);

    /**
     * @param int $id
     * @return mixed
     */
    abstract public function delete(int $id);

    /**
     * @param string $file
     * @param array $viewData
     * @return false|string|string[]
     * @throws \Exception
     */
    protected function render(string $file, array $viewData)
    {
        if (!file_exists($file)) {
            throw new \Exception('page: ' . $file . ' does not exits!');
        }

        $this->Viewer->setData($viewData);
        return $this->Viewer->render($file);
    }

    /**
     * @return bool
     */
    protected function isSubmit()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * @param string $name
     * @return mixed
     */
    protected function getData(string $name)
    {
        if (empty($_REQUEST[$name])) {
            throw new \Exception('Datafield: ' . $name . ' does not exits!');
        }

        return $_REQUEST[$name];
    }
}