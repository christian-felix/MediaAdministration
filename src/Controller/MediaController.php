<?php

namespace src\Controller;

use config\Database;
use config\Paginator;
use config\Viewer;
use src\Model\Entity;
use src\Model\Media;

/**
 * Class Media
 *
 *  here we will perform our cd media data for some testing purposes
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
     *  show all media (add paginator?)
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

        return $this->render('public/media/show.phtml', ['mediaData' => $mediaData, 'username' => 'Christian', 'Paginator' => $this->Paginator]);
    }

    /**
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

        return $this->render('public/media/add.phtml', ['mediaData' => '']);
    }

    /**
     *  remove
     */
    public function delete(int $id)
    {
        //valide delete
        $result = $this->em->delete($id);


        //TODO: reload instead header() relocation
        header('location: http://' . $_SERVER['SERVER_NAME'].'/media');
    }

    /**
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

        return $this->render('public/media/edit.phtml', ['mediaData' => $media]);
    }

    /**
     * @param int $id
     */
    public function view(int $id)
    {
        //TODO:
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