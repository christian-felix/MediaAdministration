<?php

namespace src\Controller;

use src\Model\User;

/**
 * Class UserController
 * @package src\Controller
 * @author Christian Felix
 */
class UserController extends AbstractController
{
    /**
     * @param int $id
     * @return mixed|void
     */
    public function view(int $id)
    {
        return $this->render('src/Templates/user/view.phtml', ['user' => $id]);
    }

    /**
     * @return false|string|string[]
     * @throws \Exception
     */
    public function add()
    {
        if ($this->isSubmit()) {

            $user = new User();

            $user->setName($this->getData('name'));
            $user->setPassword($this->getData('password'));
            $user->setEmail($this->getData('email'));
            $user->setRole($this->getData('role'));
            $user->setActive($this->getData('active'));

            if (!$this->em->insert($user)){
                throw new \Exception('User creation has failed!');
            }

            //TODO: redirect to view
        }

        return $this->render('src/Templates/user/add.phtml', ['navi' => 'user/navi.phtml']);
    }

    /**
     * @param int $id
     * @return mixed|void
     */
    public function edit(int $id)
    {
        if ($this->isSubmit()) {


        }

        return $this->render('src/Templates/user/edit.phtml', ['user' => $id]);
    }

    /**
     * @param int $id
     * @return mixed|void
     */
    public function delete(int $id)
    {

    }
}