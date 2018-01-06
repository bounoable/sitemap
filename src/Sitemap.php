<?php

namespace Bounoable\Sitemap;

class Sitemap implements Renderable
{
    /**
     * The pages.
     *
     * @var array
     */
    protected $pages = [];

    /**
     * Add a page to the sitemap.
     */
    public function page(string $location, float $priority, $modified = null): Page
    {
        $this->pages[] = $page = new Page($location, $priority, $modified);

        return $page;
    }

    /**
     * Get the pages.
     */
    public function pages(): array
    {
        return $this->pages;
    }

    /**
     * Render the sitemap to an XML string.
     */
    public function render(): string
    {
        return sprintf('<?xml version="1.0" encoding="UTF-8"?>
            <urlset
                xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                xmlns:xhtml="http://www.w3.org/1999/xhtml"
                xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
                xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
                        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
                %s
            </urlset>
        ', $this->renderPages());
    }

    /**
     * Render the pages to an XML string.
     */
    protected function renderPages(): string
    {
        return (string)implode("\n", array_map(function (Page $page) {
            return $page->render();
        }, $this->pages()));
    }
}
