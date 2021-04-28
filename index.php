<?php


use config\Router;

spl_autoload_register(function ($className) {

    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    $class = $_SERVER['DOCUMENT_ROOT'] . '/' . $className . '.php';
    require_once($class);
});

try {
    session_start();
    $router = new Router(new \config\Viewer(), new \config\Request());
    $response = $router->handleRequest($_SERVER['REQUEST_URI']);

} catch (Throwable $e) {  // PHP 7

    die('Error occurred: ' . $e->getMessage() . ' line : ' . $e->getLine() . ' file: ' .  $e->getFile());

} catch (Exception $e) { // PHP 5

    die('Error occurred: ' . $e->getMessage() . ' line : ' . $e->getLine() . ' file: ' .  $e->getFile());
}

echo $response;
