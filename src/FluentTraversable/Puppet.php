<?php


namespace FluentTraversable;


class Puppet implements \ArrayAccess
{
    private $movements = array();

    public static function record()
    {
        return new self();
    }

    public static function object()
    {
        return self::record();
    }

    public function play($object)
    {
        return $this($object);
    }

    public function __get($name)
    {
        $this->movements[] = function($object) use($name){
            return isset($object->$name) ? $object->$name : null;
        };

        return $this;
    }

    public function __call($method, $arguments)
    {
        $this->movements[] = function($object) use($method, $arguments){
            $callable = array($object, $method);

            if(!is_callable($callable)) {
                throw new \BadMethodCallException(sprintf('Method %s::%s does not exist', get_class($object), $method));
            }

            return call_user_func_array($callable, $arguments);
        };

        return $this;
    }

    public function offsetGet($offset)
    {
        $this->movements[] = function($object) use($offset) {
            return isset($object[$offset]) ? $object[$offset] : null;
        };

        return $this;
    }

    public function __invoke($object)
    {
        foreach($this->movements as $movement) {
            if(is_object($object) || is_array($object)) {
                $object = $movement($object);
            } else {
                return null;
            }
        }

        return $object;
    }

    public function offsetExists($offset)
    {
        throw new \BadMethodCallException('Not supported');
    }

    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException('Not supported');
    }

    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException('Not supported');
    }
}