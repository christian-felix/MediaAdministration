<?php


namespace src\Controller;

/**
 * Class RegisterController
 * @package src\Controller
 */
class RegisterController extends IndexController
{

    public function register()
    {



        $this->render('Templates/user/register.phtml', []);
    }
}