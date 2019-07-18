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
		$this->getExpectedException(SeoException::CLASSNAME);

	}

	public function testFactoryBuildSitemap()
	{
		$this->assertInstanceOf(SeoInterface::CLASSNAME, Factory::sitemap('https://example.com'));

		$this->assertInstanceOf(SitemapInterface::CLASSNAMENAME, Factory::sitemap('https://example.com'));

		$this->assertInstanceOf(SitemapIndexInterface::CLASSNAMENAMENAME, Factory::sitemap('https://example.com'));
	}


	public function testFactoryBuildMetaTags()
	{
		$this->assertInstanceOf(SeoInterface::CLASSNAME, Factory::metaTags());

		$this->assertInstanceOf(MetaTagsInterface::CLASSNAMENAME, Factory::metaTags());
	}


	public function testFactorySchema()
	{
		$this->assertInstanceOf(SeoInterface::CLASSNAME, Factory::schema('organization'));

		$this->assertInstanceOf(SchemaInterface::CLASSNAMENMAE, Factory::schema('article'));
	}


	public function testFactoryBuildPing()
	{
		$this->assertInstanceOf(SeoInterface::CLASSNAME, Factory::ping());
	}

}
