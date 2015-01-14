<?php namespace MicheleAngioni\Support\Presenters;

use ArrayAccess;
 
abstract class AbstractPresenter implements ArrayAccess {
	
	/**
	 * The object to present
	 *
	 * @var mixed
	 */
	protected $object;
	
	
	/**
	 * Check to see if the offset exists on the current object
	 *
	 * @param string $key
	 * @return boolean
	 */
	public function offsetExists($key)
	{
		return isset($this->object[$key]);
	}
	
	/**
	 * Retrieve the key from the object as if it were an array
	 *
	 * @param string $key
	 * @return boolean
	 */
	public function offsetGet($key)
	{
        return $this->__get($key);
	}
	
	/**
	 * Set a property on the object as if it were any array
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function offsetSet($key, $value)
	{
		$this->object[$key] = $value;
	}
	
	/**
	 * Unset a key on the object as if it were an array
	 *
	 * @param string $key
	 */
	public function offsetUnset($key)
	{
		unset($this->object[$key]);
	}
	
	/**
	 * Inject the object to be presented
	 *
	 * @param mixed
	 */
	public function set($object)
	{
		$this->object = $object;
	}
	
	/**
	 * Check to see if there is a presenter method. If not pass to the object
	 *
	 * @param string $key
	 */
	public function __get($key)
	{
		if (method_exists($this, $key))
		{
			return $this->{$key}();
		}
	
		return $this->object->$key;
	}

}
