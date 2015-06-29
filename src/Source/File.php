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
class File implements Source
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @param string $path Path to a file.
     * @throws Exception
     */
    public function __construct($path)
    {
        $this->code = @file_get_contents($path);
        if ($this->code === false) {
            throw new Exception('Failed to load source code from '.$path);
        }

        // strip BOM, if any
        if (substr($this->code, 0, 3) == "\xef\xbb\xbf") {
            $this->code = substr($this->code, 3);
        }
    }

    /**
     * @return string The source code.
     */
    public function get()
    {
        return $this->code;
    }
}
