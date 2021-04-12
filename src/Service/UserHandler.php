<?php


namespace src\Service;

/**
 * Class UserHandler
 * @package src\Service
 */
class UserHandler
{

    public function register()
    {
        //TODO:
        $email = '';
        $subject = '';
        $message = '';
        if (!mail($email, $subject, $message)){
            throw new \Exception('Email could not be sent ');
        }
    }
}