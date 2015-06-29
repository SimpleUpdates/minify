<?php
namespace MatthiasMullie\Minify\Source;

/**
 * Please report bugs on https://github.com/matthiasmullie/minify/issues
 *
 * @author Matthias Mullie <minify@mullie.eu>
 *
 * @copyright Copyright (c) 2012, Matthias Mullie. All rights reserved.
 * @license MIT License
 */
interface Source
{
    /**
     * @return string The source code.
     */
    public function get();
}
