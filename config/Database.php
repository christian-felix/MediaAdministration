<?php

namespace config;

use src\Model\Entity;
use src\Kernel\ConfigReader;

/**
 * Class Database
 * @package config
 * @author Christian Felix
 */
class Database
{
    private static $instance;
    private $conn;
    private $config = [];

    /**
     * Database constructor.
     * use singleton for database connection
     */
    private function __construct(ConfigReader $configReader)
    {
        $configReader->readConfig();
        $this->config = $configReader->getConfig('_database');

        $this->connect();
    }

    /**
     * @return Database
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new Database(new ConfigReader());
        }

        return self::$instance;
    }

    /**
     * @throws \Exception
     */
    private function connect()
    {
        $this->conn = new \mysqli($this->config['host'], $this->config['user'], $this->config['pass'], $this->config['database']);

        if ($this->conn->connect_error) {
            throw new \Exception('Database connection could not be established: ' . $this->conn->connect_error);
        }
    }

    /**
     * @param Entity $entity
     * @return mixed
     * @throws \Exception
     */
    public function insert(Entity $entity)
    {
        $EntityHelper = new EntityHelper($entity);
        $EntityHelper->setMethodName('insert');
        $query = $EntityHelper->insert();
        $this->conn->autocommit(false);

        if (!$this->conn->query($query)) {
            throw new \Exception('Insert has failed ' . $query);
        }

        $lastId = $this->conn->insert_id;

        if (!$this->conn->commit()){
            throw new \Exception('Commit has failed' . $query);
        }

        return $lastId;
    }

    /**
     * @param Entity $entity
     * @throws \Exception
     */
    public function update(Entity $entity)
    {
        $EntityHelper = new EntityHelper($entity);
        $EntityHelper->setMethodName('update');
        $query = $EntityHelper->update();

        $this->conn->autocommit(false);
        $this->conn->query($query);

        if (!$this->conn->commit()){
            throw new \Exception('Commit has failed' . $query);
        }
    }

    /**
     * @param Entity $entity
     * @throws \Exception
     */
    public function delete(Entity $entity)
    {
        $EntityHelper = new EntityHelper($entity);
        $EntityHelper->setMethodName('delete');
        $query = $EntityHelper->delete();

        $this->conn->query($query);
    }

    /**
     * @param string $query
     * @return mixed
     */
    public function findBy(string $query)
    {
        $statement = $this->conn->query($query);
        $result = [];

        if ($statement) {
            $result = $statement->fetch_all(MYSQLI_ASSOC);
            $statement->free_result();
        }

        return $result;
    }

    /**
     * @param string $query
     * @param $options
     * @return mixed
     */
    public function findOneBy(string $query)
    {
        $statement = $this->conn->query($query);
        $result = [];

        if ($statement) {
            $result = $statement->fetch_object();
            $statement->free_result();
        }

        return $result;
    }
}
