<?php

namespace Bounoable\Sitemap;

class Image implements Renderable
{
    /**
     * The image location.
     *
     * @var string
     */
    protected $location;

    /**
     * Create an image.
     */
    public function __construct(string $location)
    {
        $this->location = $location;
    }

    /**
     * Get the location.
     */
    public function location(): string
    {
        return $this->location;
    }

    /**
     * Render the image to an XML string.
     */
    public function render(): string
    {
        return sprintf('
            <image:image>
                <image:loc>%s</image:loc>
            </image:image>
        ', $this->location());
    }
}
