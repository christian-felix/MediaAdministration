<?php

namespace src\Controller;

use config\Viewer;

/**
 * Class AbstractController
 * @package src\Controller
 */
abstract class AbstractController
{
    /**
     * @var Viewer
     */
    protected $Viewer;

    /**
     * AbstractController constructor.
     * @param Viewer $viewer
     */
    public function __construct(Viewer $viewer)
    {
        $this->Viewer = $viewer;
    }

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
}