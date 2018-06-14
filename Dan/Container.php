<?php
namespace Dan;

use ArrayAccess;

class Container implements ArrayAccess  
{
    private $container = array();
    public function __construct($values) {
        $this->container = isset($values) ? $values : [];
    }
    public function offsetSet($offset, $value) {
        if ($value instanceof Closure) {
            if (is_null($offset)) {
                $this->container[] = $value;
            } else {
                $this->container[$offset] = $value;
            }
        }
        if(is_callable($value)){
            if (is_null($offset)) {
                $this->container[] = $value($this);
            } else {
                $this->container[$offset] = $value($this);
            }
        }
    }
    public function offsetExists($offset) {
        return isset($this->container[$offset]);
    }
    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }
    public function offsetGet($offset) {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }
    public function get($offset='')
    {
        return $this->offsetGet($offset);
    }
    public function has($id)
    {
        return $this->offsetExists($id);
    }
    /********************************************************************************
     * Magic methods for convenience
     *******************************************************************************/
    /**
     * 当请求的方法不存在
     */
    public function __get($name)
    {
        return $this->get($name);
    }
    public function __isset($name)
    {
        return $this->has($name);
    }
}
