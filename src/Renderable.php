<?php

namespace Bounoable\Sitemap;

interface Renderable
{
    /**
     * Render the object to an XML string.
     */
    public function render(): string;
}
