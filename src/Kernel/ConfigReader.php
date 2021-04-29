<?php

namespace src\Kernel;

#define('PROJECT_ROOT', dirname(dirname(__FILE__)));
#define('PROJECT_LIBS', PROJECT_ROOT . '/vendors/libs');

/**
 * Class ConfigReader
 * @package src\Kernel
 * @author Christian Felix
 */
class ConfigReader
{
    const OPTION_1 = 1;
    const OPTION_2 = 2;
    const OPTION_3 = 3;

    static $OPTION = 3;

    protected $filename = 'config.json';
    protected $filepath = null;
    protected $content = [];

    /**
     * ConfigReader constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/config/' . $this->filename)) {
            throw new \Exception('config file missing...');
        }

        $this->filepath = $_SERVER['DOCUMENT_ROOT']. '/config/' . $this->filename;
    }

    public function readConfig()
    {
        $content = $this->getFileContent();

        if (empty($content)){
            throw new \Exception('Configfile content is empty!');
        }

        $this->content = json_decode($content, true);
    }

    /**
     * @param string $name
     * @return array
     * @throws \Exception
     */
    public function getConfig(string $name) : array
    {
        if (!array_key_exists($name, $this->content)) {
            throw new \Exception('Config settings ' . $name . ' does not exists!');
        }

        return $this->content[$name];
    }

    /**
     * @return string
     */
    private function getFileContent() : string
    {
        $content = '';

        switch(self::$OPTION) {

            case 1:
                if (readfile($this->filepath, false) !== false) {
                    $content = ob_get_contents();
                }
                break;
            case 2:
                $content = file_get_contents($this->filepath);
                break;
            case 3:
                $fp = fopen($this->filepath, "r");
                while (!feof($fp)) {
                    $content .= fread($fp, 1024);
                }
                fclose($fp);
                break;
        }

        return $content;
    }
}