<?php

namespace src\Controller;

use config\Database;
use src\Service\Paginator;
use config\Viewer;
use src\Model\Media;

/**
 * Class MediaController
 * @package src\Controller
 */
class MediaController extends AbstractController
{
    /**
     * @var Database
     */
    private $em;

    /**
     * @var string
     */
    private $tbl_name = 'media';

    /**
     * @var Paginator
     */
    protected $Paginator;

    /**
     * MediaController constructor.
     * @param Viewer $viewer
     */
    public function __construct(Viewer $viewer)
    {
        parent::__construct($viewer);
        $this->em = Database::getInstance();
        $this->Paginator = new Paginator();
    }

    /**
     *  @Route("/media")
     */
    public function index()
    {
        $mediaData = [];
        $sql = 'SELECT * FROM ' . $this->tbl_name;
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

        return $this->render('src/Templates/media/show.phtml', ['mediaData' => $mediaData, 'username' => 'Administrato', 'Paginator' => $this->Paginator]);
    }

    /**
     *
     * @Route("/media/page/1")
     * @param int $page
     */
    public function page(int $page)
    {
        die("HERE");
    }

    /**
     * @Route("/media/add")
     *  add new
     */
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $date = new \DateTime();

            $media = new Media();
            $media->setTitle($_POST['title']);
            $media->setPublished($date->format('Y-m-d'));
            $media->setInterpreter($_POST['interpreter']);
            $media->setGenre($_POST['genre']);
            $media->setType($_POST['type']);

            $image = $this->uploadMedia();
            $media->setImage($image);

            $this->em->insert($media);

            header('location: http://' . $_SERVER['SERVER_NAME'].'/media');
        }

        return $this->render('src/Templates/media/add.phtml', ['mediaData' => '']);
    }

    /**
     * @Route("/media/delete/1")
     *
     * @param int $id
     * @return mixed|void
     */
    public function delete(int $id)
    {
        //valide delete
        $result = $this->em->delete($id);


        //TODO: reload instead header() relocation
        header('location: http://' . $_SERVER['SERVER_NAME'].'/media');
    }

    /**
     * @Route("/media/edit/1")
     *
     * @param int $id
     * @return false|string|string[]
     * @throws \Exception
     */
    public function edit(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->update();
            header('location: http://' . $_SERVER['SERVER_NAME'].'/media');
        }

        $sql = 'SELECT * FROM ' . $this->tbl_name. ' WHERE id = ' . $id;
        $result = $this->em->findOneBy($sql);

        $media = new Media();
        $media->setId($result->id);
        $media->setTitle($result->title);
        $media->setPublished($result->published);
        $media->setInterpreter($result->interpreter);
        $media->setGenre($result->genre);
        $media->setType($result->type);
        $media->setImage($result->image);

        return $this->render('src/Templates/media/edit.phtml', ['mediaData' => $media]);
    }

    /**
     *
     * @Route("media/view/1")
     *
     * @param int $id
     */
    public function view(int $id)
    {
        //TODO:

        die("inside : ".__METHOD__);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    protected function uploadMedia()
    {
        $name = $_FILES['mediafile']['name'];

        if (!move_uploaded_file( $_FILES['mediafile']['tmp_name'],  $_SERVER["DOCUMENT_ROOT"] . '/public/images/' . $name )){
            throw new \Exception('Fileupload has failed ' . $name);
        }

        return $name;
    }


    /**
     * @throws \Exception
     */
    protected function update()
    {
        $date = new \DateTime();

        $media = new Media();
        $media->setId($_POST['id']);
        $media->setTitle($_POST['title']);
        $media->setPublished($date->format('Y-m-d'));
        $media->setInterpreter($_POST['interpreter']);
        $media->setGenre($_POST['genre']);
        $media->setType($_POST['type']);

        $image = $this->uploadMedia();
        $media->setImage($image);

        $this->em->update($media);
    }
}