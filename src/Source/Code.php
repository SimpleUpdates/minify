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
class Code implements Source
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @param string $code Source code.
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * @return string The source code.
     */
    public function get()
    {
        return $this->code;
    }
}
