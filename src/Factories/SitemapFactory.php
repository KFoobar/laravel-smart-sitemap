<?php

namespace KFoobar\SmartSitemap\Factories;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use SimpleXMLElement;

class SitemapFactory
{
    /**
     * @var Collection
     */
    protected Collection $urls;

    /**
     * Constructs a new instance.
     */
    public function __construct()
    {
        $this->urls = collect();
    }

    /**
     * Add a URL to the sitemap.
     *
     * @param  string       $url
     * @param  Carbon|DateTime|string|null  $modified
     *
     * @return self
     */
    public function add(string $url, $modified = null): self
    {
        $this->urls->push([
            'url' => $url,
            'lastmod' => $this->formatLastModified($modified),
        ]);

        return $this;
    }

    /**
     * Returns a response with the sitemap.
     *
     * @return string
     */
    public function view(): Response
    {
        $xml = $this->generate();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Returns the sitemap.
     *
     * @return string
     */
    public function generate(): string
    {
        $xml = $this->initializeSitemapXml();

        $this->urls->each(function ($url) use ($xml) {
            $element = $xml->addChild('url');
            $element->addChild('loc', $url['url']);

            if (!empty($url['lastmod'])) {
                $element->addChild('lastmod', $url['lastmod']);
            }
        });

        return $xml->asXML();
    }

    /**
     * Format the last modified date.
     *
     * @param  Carbon|DateTime|string|null  $lastModified
     * @return string|null
     */
    private function formatLastModified($lastModified): ?string
    {
        if ($lastModified instanceof Carbon || $lastModified instanceof DateTime) {
            return $lastModified->format('Y-m-d');
        }

        return is_string($lastModified) && !empty($lastModified) ? $lastModified : null;
    }

    /**
     * Initialize the XML for the sitemap.
     *
     * @return SimpleXMLElement
     */
    private function initializeSitemapXml(): SimpleXMLElement
    {
        return new SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>'
        );
    }
}
