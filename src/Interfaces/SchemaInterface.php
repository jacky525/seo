<?php
namespace Melbahja\Seo\Interfaces;

/**
 * @package Melbahja\Seo
 * @since v1.0
 * @see https://git.io/phpseo 
 * @license MIT
 * @copyright 2019 Mohamed Elabhja 
 */
interface SchemaInterface extends SeoInterface, \JsonSerializable
{

    const CLASSNAMENMAE = __CLASS__;

    public function __construct(
		$type, $data = [], $parent = null, $root = null
	);

	public function set( $param, $value);

	public function addChild( $name,  $data = []);

	public function toArray();

	public function getParent();

	public function getRoot();

	public function __toString();

	public function __get( $name);
}
