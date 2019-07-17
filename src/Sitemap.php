<?php
namespace Melbahja\Seo;

use Closure;
use Melbahja\Seo\{
	Sitemap\SitemapIndex,
	Exceptions\SitemapException,
	Interfaces\SitemapIndexInterface,
	Interfaces\SitemapBuilderInterface
};

/**
 * @package Melbahja\Seo
 * @since v1.0
 * @see https://git.io/phpseo 
 * @license MIT
 * @copyright 2019 Mohamed Elabhja 
 */
class Sitemap implements SitemapIndexInterface
{

	/**
	 * Sitemap options
	 * @var array
	 */
	protected $options = 
	[
		'save_path' => null,
		'index_name' => 'sitemap.xml',
		'sitemaps_url' => null
	]

	/**
	 * Sitemap files
	 * @var array
	 */
	, $sitemaps  = []

	/**
	 * Sitemaps domain name
	 * @var string
	 */
	, $domain;


	/**
	 * Initialize new sitemap builder
	 *
	 * @param string $domain The domain name only
	 * @param array  $options
	 */
	public function __construct( $domain,  $options = null)
	{
		$this->domain = $domain;

		if ($options !== null) {

			$this->setOptions($options);
		}
	}

	/**
	 * Set builer options
	 *
	 * @param array $options
	 * @return SitemapIndexInterface
	 */
	public function setOptions( $options)
	{
		$this->options = array_merge($this->options, $options);

		return $this;
	}

	/**
	 * Get all sitemap options
	 *
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * Set save path
	 *
	 * @param string $path
	 * @return SitemapIndexInterface
	 */
	public function setSavePath( $path)
	{
		$this->options['save_path'] = $path;
		return $this;
	}

	/**
	 * Get save path
	 *
	 * @return null|string
	 */
	public function getSavePath()
	{
		return $this->options['save_path'];
	}

	/**
	 * Set index name
	 * 
	 * @param string $name
	 * @return SitemapIndexInterface
	 */
	public function setIndexName( $name)
	{
		$this->options['index_name'] = $name;
		return $this;
	}

	/**
	 * Get Index name
	 *
	 * @return string
	 */
	public function getIndexName()
	{
		return $this->options['index_name'];
	}

	/**
	 * Set sitemaps url
	 *
	 * @param string $url
	 * @return SitemapIndexInterface
	 */
	public function setSitemapsUrl( $url)
	{
		$this->options['sitemaps_url'] = $url;
		return $this;
	}

	/**
	 * Get sitemaps url
	 *
	 * @return null|string
	 */
	public function getSitemapsUrl()
	{
		return $this->options['sitemaps_url'];
	}

	/**
	 * Get sitemaps domain
	 *
	 * @return string
	 */
	public function getDomain()
	{
		return $this->domain;
	}

	/**
	 * Set sitemaps to a path
	 *
	 * @param  string $path
	 * @return bool
	 */
	public function saveTo( $path)
	{
		return SitemapIndex::build(
			$this->options['index_name'], $path, (isset($this->options['sitemaps_url']) ? $this->options['sitemaps_url'] :$this->domain), $this->sitemaps
		);
	}

	/**
	 * {@method saveTo} by pre defined save_path option 
	 * 
	 * @param  string $path
	 * @return bool
	 */
	public function save()
	{
		if (is_string($this->options['save_path']) === false) {

			throw new SitemapException('Invalid or missing save_path option'); 
		}

		return $this->saveTo($this->options['save_path']);
	}


	/**
	 * Generate sitemaps
	 *
	 * @param  SitemapBuilderInterface $builder
	 * @param  array $options
	 * @param  callable $func
	 * @return SitemapIndexInterface
	 */
	public function build( $builder,  $options,  $func)
	{
		if (isset($this->sitemaps[$options['name']])) {

			throw new SitemapException("The sitemap {$name} already registred!");
		}

		# Call generator
		call_user_func_array($func, [$builder]);

		return $this->setBuilder($options['name'], $builder);
	}
    private static $instance = NULL;
	/**
	 * Sitemaps generator
	 *
	 * @param  string $builder
	 * @param  array  $args
	 * @return SitemapIndexInterface
	 */
	public function __call( $builder,  $args)
	{
		if (class_exists($builder = '\Melbahja\Seo\Sitemap\\' . ucfirst($builder) . 'Builder')) {

			if (count($args) !== 2) {

				throw new SitemapException("Invalid {$builder} arguments");
			
			} elseif (is_string($args[0])) {

				$args[0] = ['name' => $args[0]];
			}

			if (isset($args[0]['name']) === false) {

				throw new SitemapException("Sitemap name is required for {$builder}");
			}
//            echo $this->domain."====".$args[0];
//			var_dump($args[0]);
//			exit;
//
//            $rConstruct = new \ReflectionMethod($builder, '__construct');
//            $numParams      = $rConstruct->getNumberOfParameters();
//            $arr = $args[0];
//
//            $tempArray = array_fill(0, $numParams, $this->domain);
//            $arr    = ($arr + $tempArray);
//
//
//            $reflection = new \ReflectionClass($builder);
//            self::$instance = $reflection->newInstanceArgs($arr);


//            $reflection = new \ReflectionClass($builder);
//			$arr = $args[0];
//            array_unshift($arr, $this->domain);
//            self::$instance = $reflection->newInstanceArgs($arr);

//            $reflection = new \ReflectionClass($builder($this->domain, $args[0]));
//            self::$instance = $reflection->newInstanceArgs($args);
//            $arr2 = $args;
//            array_unshift($arr2, self::$instance);
//            return call_user_func_array(array($this, "build"), $arr2);


			return $this->build(new $builder($this->domain, $args[0]), ...$args);
		}

		throw new SitemapException("Sitemap builder {$builder} not exists");
	}

	/**
	 * Build registred sitemap and save it on temp
	 *
	 * @param string                  $name
	 * @param SitemapBuilderInterface $builder
	 * @return SitemapIndexInterface
	 */
	protected function setBuilder( $name,  $builder)
	{
		$this->sitemaps[$name] = $builder->saveTemp();

		return $this;
	}
}
