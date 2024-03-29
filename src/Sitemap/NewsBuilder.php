<?php
namespace Melbahja\Seo\Sitemap;

use Melbahja\Seo\Exceptions\SitemapException;
use Melbahja\Seo\Interfaces\SitemapBuilderInterface;


/**
 * @package Melbahja\Seo
 * @since v1.0
 * @see https://git.io/phpseo 
 * @license MIT
 * @copyright 2019 Mohamed Elabhja 
 */
class NewsBuilder extends SitemapBuilder 
{

	/**
	 * Default publication
	 * @var array
	 */
	private $publication = ['name' => null, 'lang' => null];

	/**
	 * Initialize NewsBuilder
	 *
	 * @param string     $domain
	 * @param array|null $options
	 * @param string     $ns
	 */
	public function __construct( $domain,  $options = null,  $ns = '')
	{
		parent::__construct($domain, $options, $ns .' xmlns:news="'. static::NEWS_NS . '"');
	}


	/**
	 * Set dafault publication
	 *
	 * @param string $name
	 * @param string $lang
	 * @return SitemapBuilderInterface
	 */
	public function setPublication( $name,  $lang)
	{
		$this->publication = 
		[
			'name' => $name,
			'lang' => $lang
		];

		return $this;
	}

	/**
	 * Get publication
	 *
	 * @return array
	 */
	public function getPublication()
	{
		return $this->publication;
	}


	/**
	 * Set a news (Fake news not allowed ^_~)
	 *
	 * @param  array  $options
	 * @return SitemapBuilderInterface
	 */
	public function news( $options)
	{
		$options['name'] = isset($options['name']) ?$options['name'] : $this->publication['name'];
		$options['language'] = isset($options['language']) ?$options['language']: $this->publication['lang'];

		if (isset($options['name'], $options['language'], $options['publication_date'], $options['title']) === false) {

			throw new SitemapException("News map require: name, language, publication_date and title");
		}

		$this->url['news'] = $options;

		return $this;		
	}

}
