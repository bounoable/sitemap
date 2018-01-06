<?php

namespace Bounoable\Sitemap;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;

class Page implements Renderable
{
    /**
     * The location of the page.
     *
     * @var string
     */
    protected $location;

    /**
     * The priority of the page.
     *
     * @var float
     */
    protected $priority;

    /**
     * The last modified date of the page.
     *
     * @var \DateTimeInterface
     */
    protected $modified;

    /**
     * The images of the page.
     *
     * @var array
     */
    protected $images = [];

    /**
     * Create a page.
     *
     * @param  string  $location
     * @param  float  $priority
     * @param  \DateTimeInterface|string  $modified
     * @return void
     */
    public function __construct(string $location, float $priority, $modified)
    {
        $this->location = $location;
        $this->priority = $priority;
        $this->modified = is_string($modified) ? \DateTime::createFromFormat(DateTime::ATOM, $modified) : $modified;
    }

    /**
     * Get the location.
     */
    public function location(): string
    {
        return $this->location;
    }

    /**
     * Get the last modified ATOM string.
     */
    public function lastModified(): string
    {
        return $this->modified->toAtomString();
    }

    /**
     * Get the priority.
     */
    public function priority(): float
    {
        return $this->priority;
    }

    /**
     * Get the images.
     */
    public function images(): array
    {
        return $this->images;
    }

    /**
     * Addd an image.
     */
    public function image(string $location): Page
    {
        $this->images[] = new Image($location);

        return $this;
    }

    /**
     * Render the page to an XML string.
     */
    public function render(): string
    {
        return sprintf('
            <url>
                <loc>%s</loc>
                <lastmod>%s</lastmod>
                <priority>%f</priority>
                %s
            </url>
        ', $this->location(), $this->lastModified(), $this->priority(), $this->renderImages());
    }

    /**
     * Render the images to an XML string.
     */
    protected function renderImages(): string
    {
        return (string)implode("\n", array_map(function (Image $image) {
            return $image->render();
        }, $this->images()));
    }
}
