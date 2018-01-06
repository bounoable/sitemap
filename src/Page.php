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
     * @var \DateTimeInterface|null
     */
    protected $modified = null;

    /**
     * The images of the page.
     *
     * @var array
     */
    protected $images = [];

    /**
     * The alternate hreflang tags.
     *
     * @var array
     */
    protected $languages = [];

    /**
     * Create a page.
     *
     * @param  string  $location
     * @param  float  $priority
     * @param  \DateTimeInterface|string|null  $modified
     * @return void
     */
    public function __construct(string $location, float $priority, $modified = null)
    {
        $this->location = $location;
        $this->priority = $priority;

        if (!is_null($modified)) {
            $this->modified = is_string($modified) ? \DateTime::createFromFormat(DateTime::ATOM, $modified) : $modified;
        }
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
    public function lastModified(): ?string
    {
        return $this->modified ? $this->modified->toAtomString() : null;
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
     * Get the alternate language tags.
     */
    public function languages(): array
    {
        return $this->languages;
    }

    /**
     * Add an alternate language tag.
     */
    public function language(string $hreflang, string $href): Page
    {
        $this->languages[] = [
            'hreflang' => strtolower($hreflang),
            'href' => $href,
        ];

        return $this;
    }

    /**
     * Render the page to an XML string.
     */
    public function render(): string
    {
        $lastModifiedTag = $this->lastModified() ? "<lastmod>{$this->lastModified()}</lastmod>" : '';

        return sprintf('
            <url>
                <loc>%s</loc>
                %s
                %s
                <priority>%.2f</priority>
                %s
            </url>
        ', $this->location(), $this->renderLanguageLinks(), $lastModifiedTag, $this->priority(), $this->renderImages());
    }

    /**
     * Render the alternate language tags to an XML string.
     */
    protected function renderLanguageLinks(): string
    {
        return (string)implode('', array_map(function (array $alternate) {
            return sprintf('<xhtml:link rel="alternate" hreflang="%s" href="%s" />', $alternate['hreflang'], $alternate['href']);
        }, $this->languages()));
    }

    /**
     * Render the images to an XML string.
     */
    protected function renderImages(): string
    {
        return (string)implode('', array_map(function (Image $image) {
            return $image->render();
        }, $this->images()));
    }
}
