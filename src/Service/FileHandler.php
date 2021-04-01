<?php


namespace src\Service;

/**
 * Class FileHandler
 * @package src\Service
 */
class FileHandler
{
    protected string $name = '';
    protected const size = '100'; //validate max upload size (also frontend and backend site)
    protected const type = ['jpg','jpeg','gif','png'];

    /**
     * @param array $file
     * @throws \Exception
     */
    public function uploadFile(array $file)
    {
        if (empty($file['name']) || $file['size'] === 0 ) {
            //throw new \Exception('Fileupload triggered but no data has been passed!');
            return;
        }

        $this->name = $file['name']; //TODO: uuid and display name

        $size = $file['size'];
        $type = $file['type'];

        if (!move_uploaded_file($_FILES['mediafile']['tmp_name'],  $_SERVER["DOCUMENT_ROOT"] . '/public/images/' . $file['name'] )){
            throw new \Exception('Fileupload has failed while moving file: ' . $file['name']);
        }
    }

    public function getName()
    {
        return $this->name;
    }
}