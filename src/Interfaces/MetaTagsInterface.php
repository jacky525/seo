<?php
namespace Melbahja\Seo\Interfaces;

/**
 * @package Melbahja\Seo
 * @since v1.0
 * @see https://git.io/phpseo 
 * @license MIT
 * @copyright 2019 Mohamed Elabhja 
 */
interface MetaTagsInterface extends SeoInterface
{
    const CLASSNAMENAME = __CLASS__;

    public function __construct($tags = []);

	public function meta($name, $value);

	public function push($name, $attrs);

	public function build($tags);

	public function __toString();
}
