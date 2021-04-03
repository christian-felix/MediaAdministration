<?php

namespace src\Controller;

use config\Database;
use src\Model\Playlist;
use src\Service\FileHandler;
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

        $_SESSION['paginator'] = $this->Paginator;

        return $this->render('src/Templates/media/show.phtml', ['username' => 'Administrator', 'Paginator' => $this->Paginator]);
    }

    /**
     *
     * @Route("/media/page/1")
     * @param int $page
     */
    public function page(int $page)
    {
        $this->Paginator = $_SESSION['paginator'];
        $this->Paginator->setPage($page);

        $_SESSION['paginator'] = $this->Paginator;

        return $this->render('src/Templates/media/show.phtml', [ 'username' => 'Administrator', 'Paginator' => $this->Paginator]);
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
            if ($image) {
                $media->setImage($image);
            }

            $lastID = $this->em->insert($media);

            if (is_array($_POST['playlist_title']) && !empty($_POST['playlist_title'])) {

                foreach ($_POST['playlist_title'] as $key => $title){

                    $title = $_POST['playlist_title'][$key];
                    $duration = $_POST['playlist_duration'][$key];

                    $playlist = new Playlist();
                    $playlist->setMediaId($lastID);
                    $playlist->setTitle($title);
                    $playlist->setDuration($duration);

                    $this->em->insert($playlist);
                }
            }

            //TODO: output success/failure message and redirect to the last page
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

        $result = $this->em->delete($media);

        if (!$result) {
            //TODO: output some info when delete action failed
        }

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

        //TODO: view Playlist

        return $this->render('src/Templates/media/view.phtml', ['mediaData' => $media]);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    protected function uploadMedia()
    {
        $fileHandler = new FileHandler();
        $fileHandler->uploadFile($_FILES['mediafile']);

        return $fileHandler->getName();
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