<?php

namespace config;

use src\Model\Entity;

/**
 * Class Database
 */
class Database
{
    private static $host = 'localhost';
    private static $pass = 'admin';
    private static $user = 'admin';
    private static $database = 'convento';
    private static $instance;
    private $conn;

    /**
     * Database constructor.
     * use singleton for database connection
     */
    private function __construct()
    {
        $this->connect();
    }

    /**
     * @return Database
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    /**
     * @throws \Exception
     */
    private function connect()
    {
        $this->conn = new \mysqli(self::$host, self::$user, self::$pass, self::$database);

        if ($this->conn->connect_error) {
            throw new \Exception('Database connection could not be established' . $this->conn->connect_error);
        }
    }

    /**
     * @param Entity $entity
     * @throws \Exception
     */
    public function insert(Entity $entity)
    {
        $query = 'INSERT INTO media(type, title, interpreter, image) VALUES ("' . $entity->getType() . '", "' . $entity->getTitle() . '", "' . $entity->getInterpreter() . '","' . $entity->getImage() . '")';

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
    public function update(Entity $entity)
    {
        $query = 'UPDATE media SET type = "'. $entity->getType() . '" , title = "' . $entity->getTitle() . '" , interpreter = "' . $entity->getInterpreter() . '" , image = "' . $entity->getImage() . '"      WHERE id = ' . $entity->getId();

        $this->conn->autocommit(false);
        $this->conn->query($query);

        if (!$this->conn->commit()){
            throw new \Exception('Commit has failed' . $query);
        }
    }

    /**
     * @param int $id
     */
    public function delete(int $id)
    {
        $query = 'DELETE FROM media WHERE id = ' . $id;
        $this->conn->query($query);
    }

    /**
     * @param string $query
     * @return mixed
     */
    public function findBy(string $query)
    {
        $statement = $this->conn->query($query);
        $result = $statement->fetch_all(MYSQLI_ASSOC);
        $statement->free_result();

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
        $result = $statement->fetch_object();
        $statement->free_result();

        return $result;
    }
}