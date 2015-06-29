<?php
namespace MatthiasMullie\Minify\Target;

use Psr\Cache\CacheItemInterface;

/**
 * Please report bugs on https://github.com/matthiasmullie/minify/issues
 *
 * @author Matthias Mullie <minify@mullie.eu>
 *
 * @copyright Copyright (c) 2012, Matthias Mullie. All rights reserved.
 * @license MIT License
 */
class Cache implements Target
{
    /**
     * @var CacheItemInterface
     */
    protected $item;

    /**
     * @param CacheItemInterface $item Cache item to write the data to.
     */
    public function __construct(CacheItemInterface $item)
    {
        $this->item = $item;
    }

    /**
     * @param string $content The minified data.
     */
    public function store($content)
    {
        $this->item->set($content);
    }
}
