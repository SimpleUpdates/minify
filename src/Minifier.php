<?php
namespace MatthiasMullie\Minify;

use MatthiasMullie\Minify\Source\Source;
use MatthiasMullie\Minify\Compressor\Compressor;
use MatthiasMullie\Minify\Target\Target;

/**
 * Please report bugs on https://github.com/matthiasmullie/minify/issues
 *
 * @author Matthias Mullie <minify@mullie.eu>
 *
 * @copyright Copyright (c) 2012, Matthias Mullie. All rights reserved.
 * @license MIT License
 */
class Minifier
{
    /**
     * @var Compressor
     */
    protected $compressor;

    /**
     * @var Source[]
     */
    protected $sources = array();

    /**
     * @var Target[]
     */
    protected $targets = array();

    /**
     * @param Compressor $compressor
     */
    public function __construct(Compressor $compressor)
    {
        $this->compressor = $compressor;
    }

    /**
     * @param Source $source
     */
    public function addSource(Source $source)
    {
        $this->sources[] = $source;
    }

    /**
     * @param Target $target
     */
    public function addTarget(Target $target)
    {
        $this->targets[] = $target;
    }

    public function execute()
    {
        // @todo: should do something different with sources, but this'll do for now!
        $content = '';
        foreach ($this->sources as $source) {
            $content .= $source->get();
        }
        $minified = $this->compressor->minify($content);

        foreach ($this->targets as $target) {
            $target->store($minified);
        }
    }
}
