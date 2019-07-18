<?php
namespace Tests\Melbahja\Seo;

use Melbahja\Seo\Factory;
use Melbahja\Seo\Exceptions\SeoException;
use Melbahja\Seo\Exceptions\SitemapException;
use Melbahja\Seo\Interfaces\SeoInterface;
use Melbahja\Seo\Interfaces\SchemaInterface;
use Melbahja\Seo\Interfaces\MetaTagsInterface;
use Melbahja\Seo\Interfaces\SitemapInterface;
use Melbahja\Seo\Interfaces\SitemapIndexInterface;
use Melbahja\Seo\Interfaces\SitemapBuilderInterface;


class FactoryTest extends TestCase
{

	public function testFactoryBuildExceptions()
	{
		$this->getExpectedException(SeoException::class);

	}

	public function testFactoryBuildSitemap()
	{
		$this->assertInstanceOf(SeoInterface::class, Factory::sitemap('https://example.com'));

		$this->assertInstanceOf(SitemapInterface::class, Factory::sitemap('https://example.com'));

		$this->assertInstanceOf(SitemapIndexInterface::class, Factory::sitemap('https://example.com'));
	}


	public function testFactoryBuildMetaTags()
	{
		$this->assertInstanceOf(SeoInterface::class, Factory::metaTags());

		$this->assertInstanceOf(MetaTagsInterface::class, Factory::metaTags());
	}


	public function testFactorySchema()
	{
		$this->assertInstanceOf(SeoInterface::class, Factory::schema('organization'));

		$this->assertInstanceOf(SchemaInterface::class, Factory::schema('article'));
	}


	public function testFactoryBuildPing()
	{
		$this->assertInstanceOf(SeoInterface::class, Factory::ping());
	}

}
