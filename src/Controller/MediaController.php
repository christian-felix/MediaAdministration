<?php

namespace src\Controller;

use config\Database;
use config\Viewer;
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

    /**
     * MediaController constructor.
     * @param Viewer $viewer
     */
    public function __construct(Viewer $viewer)
    {
        parent::__construct($viewer);
        $this->em = Database::getInstance();
    }

    /**
     *  show all media (add paginator?)
     */
    public function index()
    {
        $sql = 'SELECT * FROM ' . $this->tbl_name;
        $result = $this->em->select($sql);

        $mediaData = [];

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

        return $this->render('public/media/show.phtml', ['mediaData' => $mediaData]);
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

            //upload
            $image = $this->uploadMedia();
            $media->setImage($image);

            $this->em->insert($media);

            header('location: http://' . $_SERVER['SERVER_NAME'].'/media');
        }

        return $this->render('public/media/add.phtml', ['mediaData' => '']);
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
     *  remove
     */
    public function remove()
    {
        //TODO:
    }


    public function edit()
    {
        //TODO:
    }
}