<?php

namespace FluentTraversable\Internal;

use FluentTraversable\Puppet;

class PropertyGetter
{
    public function getValue($object, $path)
    {
        if($path instanceof Puppet) {
            return $path($object);
        }

        $properties = explode('.', $path);

        foreach($properties as $property) {
            $object = self::readProperty($object, $property);
        }

        return $object;
    }

    private static function readProperty($object, $property)
    {
        if(is_array($object)) {
            return isset($object[$property]) ? $object[$property] : null;
        } else if(is_object($object)) {
            $getters = array('get'.$property, 'is'.$property, $property);

            foreach($getters as $getter) {
                if(is_callable(array($object, $getter))) {
                    return $object->$getter();
                }
            }

            if(property_exists($object, $property)) {
                return $object->$property;
            }

            throw new \RuntimeException(sprintf('Property "%s" cannot be read', $property));
        }
    }
} 