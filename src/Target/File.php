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
class File implements Target
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @param string $path The path to save the minified data to.
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @param string $content The minified data.
     * @throws Exception
     */
    public function store($content)
    {
        $success = @file_put_contents($this->path, $content);
        if ($success === false) {
            throw new Exception('Failed to write minified code to '.$this->path);
        }
    }
}
