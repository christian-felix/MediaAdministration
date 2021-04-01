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
    public function getEntityCLass(): string
    {
        $matches = 0;
        if (preg_match(self::PATTERN, get_class($this->entity), $matches) === false){
            throw new \Exception('Entityname could not be determined!');
        }
        return strtolower($matches[3]);
    }

    /**
     * @return string
     */
    public function getProperties(): string
    {
        return implode(',', array_keys($this->result));
    }

    /**
     * @return string
     */
    public function getMethods(): string
    {
        $result = array_map(function($val){
            return '"'.$this->entity->{$val}().'"';
        }, array_values($this->result));

        return implode(',', $result);
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