<?php

namespace KFoobar\SmartSitemap\Tests\Feature;

use KFoobar\SmartSitemap\Factories\SitemapFactory;
use PHPUnit\Framework\TestCase;

class SitemapTest extends TestCase
{
    public function test_it_can_generate_a_sitemap()
    {
        $factory = new SitemapFactory();

        $content = $factory->add('https://example.com/my-first-post', '2025-05-01')
            ->add('https://example.com/my-second-post', '2025-05-02')
            ->generate();

        $this->assertStringContainsString('<loc>https://example.com/my-first-post</loc>', $content);
        $this->assertStringContainsString('<loc>https://example.com/my-second-post</loc>', $content);
    }

    public function test_it_handles_empty_sitemap_gracefully()
    {
        $factory = new SitemapFactory();

        $content = $factory->generate();

        $this->assertStringNotContainsString('<urlset>', $content);
        $this->assertStringNotContainsString('<url>', $content);
    }
}
