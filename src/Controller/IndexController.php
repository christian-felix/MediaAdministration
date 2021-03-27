<?php


namespace src\Controller;


class IndexController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {

        return $this->render('src/Templates/index/index.phtml',[]);
    }


    public function view(int $id)
    {
    }

    public function edit(int $id)
    {
    }

    public function delete(int $id)
    {
    }


}