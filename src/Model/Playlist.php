<?php

namespace src\Model;

/**
 * Class Playlist
 * @package src\Model
 */
class Playlist extends Entity
{
    protected $id;
    protected $media_id;
    protected $title;
    protected $duration;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getMediaId()
    {
        return $this->media_id;
    }

    /**
     * @param mixed $media_id
     */
    public function setMediaId($media_id): void
    {
        $this->media_id = $media_id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration($duration): void
    {
        $this->duration = $duration;
    }
}