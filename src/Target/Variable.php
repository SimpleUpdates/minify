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
class Variable implements Target
{
    /**
     * @var variable
     */
    protected $variable;

    /**
     * @param string $variable Variable to write the data to.
     */
    public function __construct(&$variable)
    {
        $this->variable = &$variable;
    }

    /**
     * @param string $content The minified data.
     */
    public function store($content)
    {
        $this->variable = $content;
    }
}
