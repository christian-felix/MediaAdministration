<?php

namespace src\Controller;

use src\Model\Media;
use src\Model\User;

/**
 * Class UserController
 * @package src\Controller
 * @author Christian Felix
 */
class UserController extends AbstractController
{

    public function index()
    {
        $mediaData = [];

        $sql = 'SELECT * FROM ' . $this->getTableName();
        $result = $this->em->findBy($sql);

        foreach ($result as $item) {

            $media = new Media();
            $media->setId($item['id']);
            $media->setGenre($item['genre']);
            $media->setImage($item['image']);
            $media->setInterpreter($item['interpreter']);
            $media->setTitle($item ['title']);
            $media->setPublished($item['published']);

            $mediaData[] = $media;
        }

        $this->Paginator->setResult($mediaData);

        $_SESSION['paginator'] = $this->Paginator;


        return $this->render('src/Templates/user/show.phtml',['navi' => 'media/navi.phtml',]);
    }

    /**
     * @param int $id
     * @return mixed|void
     */
    public function view(int $id)
    {
        return $this->render('src/Templates/user/view.phtml', ['navi' => 'media/navi.phtml', 'user' => $id]);
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

        return $this->render('src/Templates/user/edit.phtml', ['navi' => 'media/navi.phtml', 'user' => $id]);
    }

    /**
     * @param int $id
     * @return mixed|void
     */
    public function delete(int $id)
    {

    }
}