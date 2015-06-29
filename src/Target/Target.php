<?php
namespace MatthiasMullie\Minify\Target;

/**
 * Please report bugs on https://github.com/matthiasmullie/minify/issues
 *
 * @author Matthias Mullie <minify@mullie.eu>
 *
 * @copyright Copyright (c) 2012, Matthias Mullie. All rights reserved.
 * @license MIT License
 */
interface Target
{
    /**
     * @param string $content The minified data.
     */
    public function store($content);
}
