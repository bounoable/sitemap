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
     * The image caption.
     *
     * @var string|null
     */
    protected $caption;

    /**
     * Create an image.
     */
    public function __construct(string $location, string $caption = null)
    {
        $this->location = $location;
        $this->caption = $caption;
    }

    /**
     * Get the location.
     */
    public function location(): string
    {
        return $this->location;
    }

    /**
     * Get the caption.
     *
     * @return string|null
     */
    public function caption(): ?string
    {
        return $this->caption;
    }

    /**
     * Render the image to an XML string.
     */
    public function render(): string
    {
        $captionTag = $this->caption() ? "<image:caption>{$this->caption()}</image:caption>" : '';

        return sprintf(<<<'EOD'
<image:image>
    <image:loc>%s</image:loc>
    %s
</image:image>
EOD
        , $this->location(), $captionTag);
    }
}
