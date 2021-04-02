<?php


namespace config;

use ReflectionMethod;
use ReflectionProperty;
use src\Model\Entity;
use ReflectionClass;

/**
 * Class EntityHelper
 * @package config
 */
class EntityHelper
{
    protected $methods = [];
    protected $properties = [];
    protected $result = [];
    protected $entity = null;
    protected const PATTERN = '/(\\\\(\w+)){1,}\\\\(\w+)/';

    /**
     * EntityHelper constructor.
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $class = new ReflectionClass($entity);
        $this->entity = $entity;
        $this->methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
        $this->properties = $class->getProperties(ReflectionProperty::IS_PRIVATE | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PUBLIC);
    }

    /**
     * @param string $methodName
     */
    public function setMethodName(string $methodName)
    {
        $this->buildQuery($methodName);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function insert() : string
    {
        return 'INSERT INTO ' . $this->getEntityClass() . '(' . $this->getProperties().') VALUES ('.$this->getMethods().')';
    }

    public function update() : string
    {
        $key = 'id';
        $query = $this->getCombined();
        $where = $this->getCombinedByKey($key);

        return 'UPDATE ' . $this->getEntityCLass() . ' SET ' .$query. ' WHERE ' . $key. '=' . $where;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function delete() : string
    {
        $key = 'id';
        $where = $this->getCombinedByKey($key);

        return 'DELETE FROM ' . $this->getEntityCLass(). ' WHERE ' . $key . '=' . $where;
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function getEntityCLass(): string
    {
        $matches = 0;
        if (preg_match(self::PATTERN, get_class($this->entity), $matches) === false){
            throw new \Exception('Entity name could not be determined!');
        }
        return strtolower($matches[3]);
    }

    /**
     * @param bool $implode
     * @return array|string
     */
    protected function getProperties($implode = true)
    {
        if ($implode) {
            return implode(',', array_keys($this->result));
        }

        return array_keys($this->result);
    }


    /**
     * @param bool $implode
     * @return string|string[]
     */
    protected function getMethods($implode = true)    {
        $result = array_map(function($val){
            return '"' . $this->entity->{$val}() . '"';
        }, array_values($this->result));

        if ($implode) {
            return implode(',', $result);
        }

        return $result;
    }


    /**
     * @param bool $primarykey
     * @param bool $implode
     * @return string[]
     */
    protected function getCombined($primarykey = true, $implode = true)
    {
        $combined = array_combine($this->getProperties(false), $this->getMethods(false));

        if (!$primarykey) { //skip primary key
            $combined = array_slice($combined, 1, count($combined) - 1);
        }

        $result = array_map(function($key, $val){
            return $key  . '=' . $val;
        }, array_keys($combined), $combined);


        if ($implode) {
            implode(',', $result);
        }

        return $result;
    }

    /**
     * @param string $key
     * @return array|mixed|string
     */
    protected function getCombinedByKey(string $key)
    {
        $combined = array_combine($this->getProperties(false), $this->getMethods(false));

        if (!array_key_exists($key, $combined)) {
            throw new \Exception('key: ' . $key . ' is not available !');
        }

        return $combined[$key];
    }


    /**
     * @param string $methodType
     */
    protected function buildQuery(string $methodType)
    {
        foreach($this->properties as $property) {

            $methodName = sprintf('%s%s', 'get', ucfirst($property->getName()));

            foreach ($this->methods as $method) {

                if ($method->getName() === $methodName ) {
                    $this->result[$property->getName()] = sprintf('%s',$method->getName());
                }
            }
        }

        if ($methodType == 'insert') {
            if (array_key_exists('id', $this->result)) {
                unset($this->result['id']);
            }
        }
    }
}