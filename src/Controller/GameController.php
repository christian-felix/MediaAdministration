<?php

namespace src\Controller;

class GameController extends AbstractController {


    /**
     * @Route("/game")
     */
    public function index()
    {
        return $this->render('src/Templates/game/index.phtml',['navi' => 'game/navi.phtml','username'=>'admin']);
    }


    public function add(){}
    public function view(int $id){}
    public function edit(int $id){}
    public function delete(int $id){}

}