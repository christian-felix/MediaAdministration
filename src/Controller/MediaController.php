<?php

namespace src\Controller;

use src\Model\Playlist;
use src\Service\FileHandler;
use src\Model\Media;

/**
 * Class MediaController
 * @package src\Controller
 * @author Christian Felix
 */
class MediaController extends AbstractController
{
    /**
     *  @Route("/media")
     */
    public function index()
    {
        $mediaData = [];

        //TODO: use another approach here, get rid of sql statements in controller.
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

        return $this->render('src/Templates/media/show.phtml', ['navi' => 'media/navi.phtml', 'username' => 'Administrator', 'Paginator' => $this->Paginator]);
    }

    /**
     * @Route("/media/add")
     *  add new
     */
    public function add()
    {
        if ($this->isSubmit()) {

            $date = new \DateTime();

            $media = new Media();
            $media->setTitle($this->getData('title'));
            $media->setPublished($date->format('Y-m-d'));
            $media->setInterpreter($this->getData('interpreter'));
            $media->setGenre($this->getData('genre'));
            $media->setType($this->getData('type'));

            $image = $this->uploadMedia();
            if ($image) {
                $media->setImage($image);
            }

            $lastID = $this->em->insert($media);

            if (is_array($this->getData('playlist_title')) && !empty($this->getData('playlist_title'))) {

                foreach ($this->getData('playlist_title') as $key => $title){

                    $title = $this->getData('playlist_title')[$key];
                    $duration = $this->getData('playlist_duration')[$key];

                    $playlist = new Playlist();
                    $playlist->setMediaId($lastID);
                    $playlist->setTitle($title);
                    $playlist->setDuration($duration);

                    $id = $this->em->insert($playlist);

                    if (!$id) {
                        throw new \Exception('Playlist has failed!');
                    }
                }
            }

            //TODO: output success/failure message and redirect to the last page
            header('location: http://' . $_SERVER['SERVER_NAME'].'/media');
        }

        return $this->render('src/Templates/media/add.phtml', ['navi' => 'media/navi.phtml','mediaData' => '']);
    }

    /**
     * @Route("/media/delete/1")
     *
     * @param int $id
     * @return mixed|void
     */
    public function delete(int $id)
    {
        $sql = 'SELECT * FROM ' . $this->getTableName() . ' WHERE id = ' . $id;
        $result = $this->em->findOneBy($sql);

        $media = new Media();
        $media->setId($result->id);
        $media->setTitle($result->title);
        $media->setPublished($result->published);
        $media->setInterpreter($result->interpreter);
        $media->setGenre($result->genre);
        $media->setType($result->type);
        $media->setImage($result->image);

        $this->em->delete($media);

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
       if ($this->isSubmit()) {

            $this->update();
            header('location: http://' . $_SERVER['SERVER_NAME'].'/media');
        }

        $sql = 'SELECT * FROM ' . $this->getTableName() . ' WHERE id = ' . $id;
        $result = $this->em->findOneBy($sql);

        $media = new Media();
        $media->setId($result->id);
        $media->setTitle($result->title);
        $media->setPublished($result->published);
        $media->setInterpreter($result->interpreter);
        $media->setGenre($result->genre);
        $media->setType($result->type);
        $media->setImage($result->image);

        //Playlist (TODO: another approach needed here...)
        $sql = 'SELECT * FROM playlist WHERE media_id = ' . $media->getId() . ' ORDER BY title';
        $result2 = $this->em->findBy($sql);


        return $this->render('src/Templates/media/edit.phtml', ['navi' => 'media/navi.phtml', 'mediaData' => $media]);
    }

    /**
     *
     * @Route("media/view/1")
     *
     * @param int $id
     */
    public function view(int $id)
    {
        $sql = 'SELECT * FROM ' . $this->getTableName() . ' WHERE id = ' . $id;
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
        //Playlist (TODO: another approach needed here...)
        $sql = 'SELECT * FROM playlist WHERE mediaId = ' . $media->getId() . ' ORDER BY title';
        $result2 = $this->em->findBy($sql);

        $playlistContainer = [];

        foreach ($result2 as $playListItem) {

            $playlist = new Playlist();
            $playlist->setId($playListItem['id']);
            $playlist->setTitle($playListItem['title']);
            $playlist->setDuration($playListItem['duration']);
            $playlist->setMediaId($playListItem['mediaId']);

            $playlistContainer[] = $playlist;
        }

        return $this->render('src/Templates/media/view.phtml', ['navi' => 'media/navi.phtml', 'mediaData' => $media, 'playListContainer' => $playlistContainer ]);
    }

    /**
     * @throws \Exception
     */
    protected function update()
    {
        if ($this->isSubmit()) {

            $date = new \DateTime();
            $media = new Media();
            $media->setId($this->getData('id'));
            $media->setTitle($this->getData('title'));
            $media->setPublished($date->format('Y-m-d'));
            $media->setInterpreter($this->getData('interpreter'));
            $media->setGenre($this->getData('genre'));
            $media->setType($this->getData('type'));

            $image = $this->uploadMedia();
            $media->setImage($image);

            //check whether playlist has been added or not

            if (is_array($this->getData('playlist_title')) && !empty($this->getData('playlist_title'))) {

                foreach ($this->getData('playlist_title') as $key => $title){

                    $title = $this->getData('playlist_title')[$key];
                    $duration = $this->getData('playlist_duration')[$key];

                    $playlist = new Playlist();
                    $playlist->setMediaId($media->getId());
                    $playlist->setTitle($title);
                    $playlist->setDuration($duration);

                    //TODO: update on duplicate key
                    $id = $this->em->insert($playlist);

                    if (!$id) {
                        throw new \Exception('Playlist has failed!');
                    }
                }
            }

            $this->em->update($media);
        }
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

        return $this->render('src/Templates/media/show.phtml', ['navi' => 'media/navi.phtml', 'username' => 'Administrator', 'Paginator' => $this->Paginator]);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    protected function uploadMedia()
    {
        if (!isset($_FILES['mediafile'])) {
            throw new \Exception('Uploaded file is missing');
        }

        $fileHandler = new FileHandler();
        $fileHandler->uploadFile($_FILES['mediafile']);

        return $fileHandler->getName();
    }
}