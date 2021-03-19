<?php

namespace config;

use src\Controller\MediaController;

/**
 *
 * Class Router
 */
class Router
{
    /**
     * @var string[]
     */
    protected $routes = [
        '/login',
        '/logout',
        '/admin',
        '/index',
        '/media',
        '/media/add',
        '/media/delete'
    ];

    /**
     * @var string
     */
    protected $rootDirectory;

    /**
     * @var
     */
    protected static $Viewer;

    /**
     * Router constructor.
     * @param Viewer $viewer
     */
    public function __construct(Viewer $viewer)
    {
        $this->rootDirectory = $_SERVER['DOCUMENT_ROOT'];

        self::$Viewer = $viewer;
    }

    /**
     * @param string $request
     * @return false|string|string[]
     * @throws \Exception
     */
    public function handleRequest(string $request)
    {
        if (in_array($request, $this->routes)) {


            if ($request == '/media') {

                $mediaController = new MediaController(self::$Viewer);
                return $mediaController->index();

            }else if ($request == '/media/add'){

                $mediaController = new MediaController(self::$Viewer);
                return $mediaController->add();

            } else {

                $file =  $this->rootDirectory . '/public' . $request .'.phtml';
                return $this->render($file, ['username' => 'christian']);
            }

        } else {  //render some default page

            return $this->render('main.phtml', []);
        }
    }


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

        self::$Viewer->setData($viewData);
        return self::$Viewer->renderView($file);
    }
}