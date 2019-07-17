<?php
namespace Tests\Melbahja\Seo;

use Melbahja\Seo\{
	Factory,
	Sitemap,
	Sitemap\SitemapBuilder,
	Exceptions\SitemapException
};

class SitemapsTest extends TestCase
{

	public function testFactoryAndSitempObjects()
	{
		$this->assertEquals(new Sitemap('https://example.com'), Factory::sitemap('https://example.com'));
	}


	public function testSitemapBuilderWithNoSavePath()
	{
		$sitemap = Factory::sitemap('https://example.com');
		
		$posts = $sitemap->links(['name' => 'posts.xml'], function(SitemapBuilder $builder) 
		{
			$builder->loc('/posts/12')->priority("0.9");
			$builder->loc('/posts/13')->priority("0.9");
		});

		$this->expectException(SitemapException::class);

		$sitemap->save();
	}

	public function testNonWritableDir()
	{
		$this->expectException(SitemapException::class);

		Factory::sitemap('https://example.com', ['save_path' => '/'])->links(['name' => 't.xml'], function($builder)
		{
			$builder->loc('/about');
		
		})->save();
	}


	public function testSitemapSave()
	{
		$sitemap = $this->sitemapProvider();

		$dest = '_blog.xml';

		$articles = $sitemap->links(['name' => $dest], function($builder) 
		{
			$builder->loc('/blog/god_bless_php')->priority("0.9");
		
			$builder->loc('/blog/nx')->priority("0.0");
		});

		$this->assertTrue($sitemap->save());

		$buildedFile = $sitemap->getSavePath() .'/'. $dest;

		$this->assertFileExists($buildedFile);

		$this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>
<!-- Generated by https://git.io/phpseo -->
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>https://example.com/blog/god_bless_php</loc><priority>0.9</priority></url><url><loc>https://example.com/blog/nx</loc><priority>0.0</priority></url></urlset>',
			trim(file_get_contents($buildedFile))
		);

	}


	public function testSitemapSaveWithImages()
	{
		$sitemap = $this->sitemapProvider();

		$dest = '_blog.xml';

		$articles = $sitemap->links(['name' => $dest, 'images' => true], function($builder) 
		{
			$builder->loc('/php')
					->image('http://php.net/images/logos/php-logo.png', ['title' => 'PHP logo'])
					->image('https://pear.php.net/gifs/pearsmall.gif', ['caption' => 'php pear']);

			$builder->loc('/the-place')
					->image('/uploads/image.jpeg', ['geo_location' => '40.7590,-73.9845']);		

		});

		$this->assertTrue($sitemap->save());

		$this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>
<!-- Generated by https://git.io/phpseo -->
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"><url><loc>https://example.com/php</loc><image:image><image:title>PHP logo</image:title><image:loc>http://php.net/images/logos/php-logo.png</image:loc></image:image><image:image><image:caption>php pear</image:caption><image:loc>https://pear.php.net/gifs/pearsmall.gif</image:loc></image:image></url><url><loc>https://example.com/the-place</loc><image:image><image:geo_location>40.7590,-73.9845</image:geo_location><image:loc>https://example.com/uploads/image.jpeg</image:loc></image:image></url></urlset>',
			trim(file_get_contents($sitemap->getSavePath() . '/' .$dest))
		);

	}


	public function testEscapedUrls()
	{
		$sitemap = $this->sitemapProvider();
		$name = '_maps.xml';

		$sitemap->links(['name' => $name], function($builder) 
		{
			$builder->loc('/test/test?item=12&desc=vacation_hawaii')
					->loc('/test/123?ssasd?&');
		});

		$this->assertTrue($sitemap->save());

		$this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>
<!-- Generated by https://git.io/phpseo -->
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>https://example.com/test/test?item=12&amp;desc=vacation_hawaii</loc></url><url><loc>https://example.com/test/123?ssasd?&amp;</loc></url></urlset>',
			trim(file_get_contents($sitemap->getSavePath() . '/' . $name))
		);	
	}


	public function testVideoRequiredOptionsExceptions()
	{
		$sitemap = $this->sitemapProvider();

		$this->expectException(SitemapException::class);

		$sitemap->links(['name' => 'map.xml', 'videos' => true], function($builder)
		{
			$builder->loc('/videos/12')
					->video('Watch my new video');
		});
	}


	public function testVideoNoContentOrPlayerLocExceptions()
	{
		$sitemap = $this->sitemapProvider();

		$this->expectException(SitemapException::class);

		$sitemap->links(['name' => 'map.xml', 'videos' => true], function($builder) 
		{
			$builder->loc('/videos/12')->video('My new video',
			[
				'thumbnail' => 'https://example.com/th.jpeg',
				'description' => 'My descriptions'
			]);
		});
	}


	public function testBuildedSitemapWithVideos()
	{
		$sitemap = $this->sitemapProvider();
		$name = 'm.xml';

		$sitemap->links(['name' => $name, 'videos' => true], function($map) 
		{
			$map->loc('/blog/12')->freq('weekly')->priority('0.7')
				->loc('/blog/13')->freq('monthly')->priority('0.8')->video('My new video', 
				[
					'thumbnail' => 'https://example.com/th.jpeg',
					'description' => 'My descriptions',
					'content_loc' => 'https://example.com/video.mp4'
				]);
		});

		$this->assertTrue($sitemap->save());

		$this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>
<!-- Generated by https://git.io/phpseo -->
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"><url><loc>https://example.com/blog/12</loc><changefreq>weekly</changefreq><priority>0.7</priority></url><url><loc>https://example.com/blog/13</loc><changefreq>monthly</changefreq><priority>0.8</priority><video:video><video:description>My descriptions</video:description><video:content_loc>https://example.com/video.mp4</video:content_loc><video:title>My new video</video:title><video:thumbnail_loc>https://example.com/th.jpeg</video:thumbnail_loc></video:video></url></urlset>',
			trim(file_get_contents($sitemap->getSavePath() . "/{$name}"))
		);

	}


	public function testSitemapExistent()
	{
		$sitemap = $this->sitemapProvider();

		$sitemap->links('posts.xml', function($builder) 
		{
			$builder->loc('/post/122');
		});

		$this->assertTrue($sitemap->save());

		$this->assertFileExists($sitemap->getSavePath() . '/sitemap.xml');
	}

	public function testSitemapsWithCustomIndexName()
	{
		$sitemap = $this->sitemapProvider()->setIndexName('sitemap_index.xml');

		$sitemap->links(['name' => 'blog.xml', 'videos' => true, 'images' => true], function($map) 
		{
			$map->loc('/videos/اهلا-بالعالم')
				->video('اهلا بالعالم', 
				[
					'thumbnail' => 'https://example.com/th.jpeg',
					'description' => 'My descriptions',
					'player_loc' => 'https://example.com/embed/video/1212'
				])
				->image('https://example.com/bla_bla.jpeg')
				->freq('yearly');

			$map->loc('/blog/post/94')
				->image('https://example.com/bla_bla.jpeg', ['caption' => 'bla bla']);

			$map->loc('/categories/php');
		});

		$sitemap->links('blog_2.xml', function($map) 
		{
			$map->loc('/blog')->priority('0.5');
			$map->loc('/blog/my_first_post')->priority('0.7');
		});

		$this->assertTrue($sitemap->save());

		$this->assertFileExists($sitemap->getSavePath() . '/sitemap_index.xml');
		$this->assertFileExists($sitemap->getSavePath() . '/blog_2.xml');
		$this->assertFileExists($sitemap->getSavePath() . '/blog.xml');

		$xml = simplexml_load_file($sitemap->getSavePath() . '/sitemap_index.xml');
		
		$urls = ['https://example.com/blog_2.xml', 'https://example.com/blog.xml'];

		$this->assertSame(2, $xml->count());
		$this->assertTrue(in_array($xml->sitemap[0]->loc, $urls));
		$this->assertTrue(in_array($xml->sitemap[1]->loc, $urls));
	}


	public function testStitemapsWithCustomUrl()
	{
		$sitemap = Factory::sitemap('https://example.con',
		[
			'save_path' => sys_get_temp_dir(),
			'sitemaps_url' => 'https://example.com/sitemaps'
		]);

		$sitemap->links('blog.xml', function($map) 
		{
			$map->loc('/blog')->priority('0.5');
			$map->loc('/blog/my_first_post')->priority('0.7');
		});

		$sitemap->links('blog_2.xml', function($map) 
		{
			$map->loc('/blog')->priority('0.5');
			$map->loc('/blog/my_first_post')->priority('0.7');
		});

		$this->assertTrue($sitemap->save());

		$this->assertFileExists($sitemap->getSavePath() . '/sitemap.xml');
		$this->assertFileExists($sitemap->getSavePath() . '/blog_2.xml');
		$this->assertFileExists($sitemap->getSavePath() . '/blog.xml');

		$xml = simplexml_load_file($sitemap->getSavePath() . '/sitemap.xml');
		
		$urls = ['https://example.com/sitemaps/blog_2.xml', 'https://example.com/sitemaps/blog.xml'];

		$this->assertSame(2, $xml->count());
		$this->assertTrue(in_array($xml->sitemap[0]->loc, $urls));
		$this->assertTrue(in_array($xml->sitemap[1]->loc, $urls));
	}


	public function testMaxSitemapUrls()
	{
		$sitemap = $this->sitemapProvider();

		$this->expectException(SitemapException::class);

		$sitemap->links('max.xml', function($map) 
		{
			$i = 30001;

			do
			{
				$map->loc("/post/{$i}");
				$i--;

			} while($i !== 0);
		});
	}


	public function testNewsMaps()
	{
		$sitemap = $this->sitemapProvider();

		$sitemap->news('breaking.xml', function($map) 
		{
			$map->loc('/news/12')->news(
				[
					'name' => 'DogNews',
					'language' => 'en',
					'publication_date' => '1997-07-16T19:20:30+01:00',
					'title' => 'Breaking Cat Flying A Plane'
				])
				->loc('/news/13')->news(
				[
					'name' => 'DogNews',
					'language' => 'en',
					'publication_date' => '2000-07-16T19:22:30+01:00',
					'title' => 'Breaking Cat Flying A Private Jet With Girls'
				]);
		});

		$this->assertTrue($sitemap->save());

		$this->assertFileExists($sitemap->getSavePath() . '/breaking.xml');

		$this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>
<!-- Generated by https://git.io/phpseo -->
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="https://www.google.com/schemas/sitemap-news/0.9"><url><loc>https://example.com/news/12</loc><news:news><news:publication><news:name>DogNews</news:name><news:language>en</news:language></news:publication><news:publication_date>1997-07-16T19:20:30+01:00</news:publication_date><news:title>Breaking Cat Flying A Plane</news:title></news:news></url><url><loc>https://example.com/news/13</loc><news:news><news:publication><news:name>DogNews</news:name><news:language>en</news:language></news:publication><news:publication_date>2000-07-16T19:22:30+01:00</news:publication_date><news:title>Breaking Cat Flying A Private Jet With Girls</news:title></news:news></url></urlset>', 
			trim(file_get_contents($sitemap->getSavePath() . '/breaking.xml'))
		);

	}

	public function sitemapProvider()
	{
		return Factory::sitemap('https://example.com', ['save_path' => sys_get_temp_dir()]);
	}
}
