<?php
namespace Melbahja\Seo\Interfaces;

/**
 * @package Melbahja\Seo
 * @since v1.0
 * @see https://git.io/phpseo 
 * @license MIT
 * @copyright 2019 Mohamed Elabhja 
 */
interface SitemapBuilderInterface extends SeoInterface
{
	/**
	 * Images namespace
	 */
	const IMAGE_NS = 'http://www.google.com/schemas/sitemap-image/1.1';


	/**
	 * Videos namespace
	 */
	const VIDEO_NS = 'http://www.google.com/schemas/sitemap-video/1.1';

	/**
	 * News namespace
	 * @var string
	 */
	const NEWS_NS = 'https://www.google.com/schemas/sitemap-news/0.9';

	public function loc( $path);

	public function lastMode($date);

	public function image( $imageUrl,  $options = []);

	public function video( $title,  $options = []);

	public function changefreq( $freq);

	public function priority( $priority);

	public function saveTo( $path);

	public function saveTemp();
}
