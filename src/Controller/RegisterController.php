<?php


namespace src\Controller;

use src\Model\User;

/**
 * Class RegisterController
 * @package src\Controller
 */
class RegisterController extends IndexController
{
    /**
     * @throws \Exception
     */
    public function index()
    {
        if (!$this->checkSendMailService()) {
            throw new \Exception('Please enable your SMTP Server first');
        }

        if ($this->isSubmit()) {

            $email = $this->getData('email');

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception('Email-address seems to be invalid ' . $email);
            }
        }

        return $this->render('src/Templates/user/register.phtml', []);
    }

    /**
     * @return false
     */
    protected function checkSendMailService()
    {
        return false;
    }

    public function add()
    {
        // TODO: Implement add() method.
    }
}