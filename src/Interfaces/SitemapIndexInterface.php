<?php
namespace Melbahja\Seo\Interfaces;

/**
 * @package Melbahja\Seo
 * @since v1.0
 * @see https://git.io/phpseo 
 * @license MIT
 * @copyright 2019 Mohamed Elabhja 
 */
interface SitemapIndexInterface extends SitemapInterface
{
	public function __construct( $domain,  $options = null);

	public function setOptions( $options);

	public function getOptions();

	public function saveTo( $path);

	public function save();

	public function build( $builder,  $options,  $func);

	public function __call( $builder,  $args);
}
