<?php
namespace Melbahja\Seo;

use Melbahja\Seo\Interfaces\SchemaInterface;

/**
 * @package Melbahja\Seo
 * @since v1.0
 * @see https://git.io/phpseo 
 * @license MIT
 * @copyright 2019 Mohamed Elabhja 
 */
class Schema implements SchemaInterface
{

	/**
	 * Data
	 * @var array
	 */
	protected $data = 
	[
		'@context' => 'https://schema.org',
		'@type' => null
	]

	/**
	 * Schema root
	 * @var null|SchemaInterface
	 */
	, $root

	/**
	 * Child parent
	 * @var null|SchemaInterface
	 */
	, $parent;


	/**
	 * @param string               $type
	 * @param array                $data
	 * @param SchemaInterface|null $parent
	 * @param SchemaInterface|null $root
	 */
	public function __construct( $type,  $data = [],  $parent = null,  $root = null)
	{
		$this->data = array_merge($this->data, $data);
		$this->data['@type'] = ucfirst($type);

		if ($parent !== null) {
			
			unset($this->data['@context']);
		}

		$this->parent = $parent;
		$this->root = $root;
	}

	/**
	 * Set a property
	 *
	 * @param  string $param
	 * @param  array|scalar|SchemaInterface $value
	 * @return SchemaInterface
	 */
	public function set( $param, $value)
	{
		$this->data[$param] = $value;
		return $this;
	}

	/**
	 * Add child
	 *
	 * @param string $name
	 * @param array  $data
	 * @return SchemaInterface The child object
	 */
	public function addChild( $name,  $data = [])
	{
		$this->set($name, $child = new static($name, $data, $this, isset($this->root) ? $this->root :$this));
	
		return $child;
	}

	/**
	 * Get data as array
	 *
	 * @return array
	 */
	public function toArray()
	{
		$data = [];

		foreach ($this->data as $k => $v)
		{
			if (is_object($v)) {

				$data[$k] = $v->toArray();

				continue;
			}

			$data[$k] = $v;
		}

		return $data;
	}

	/**
	 * Get parent schema
	 *
	 * @return null|SchemaInterface
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * Get root schema
	 *
	 * @return null|SchemaInterface
	 */
	public function getRoot()
	{
		return $this->root;
	}

	/**
	 * Serialize current to json
	 *
	 * @return array
	 */
	public function jsonSerialize()
	{
		return $this->toArray();
	}

	/**
	 * Serialize root schema
	 *
	 * @return string
	 */
	public function __toString()
	{
		return '<script type="application/ld+json">'. json_encode(isset($this->root) ?$this->root: $this) .'</script>';
	}

	/**
	 * @see {@method set}
	 * @return SchemaInterface
	 */
	public function __call( $param,  $value)
	{
		return $this->set($param, ...$value);
	}

	/**
	 * Get new schema child
	 *
	 * @param  string $name
	 * @return SchemaInterface
	 */
	public function __get( $name)
	{
		return $this->addChild($name);
	}
}
