<?php
namespace MatthiasMullie\Minify;

use MatthiasMullie\Minify\Compressor;
use MatthiasMullie\Minify\Source;
use MatthiasMullie\Minify\Target;
use Psr\Cache\CacheItemInterface;

/**
 * Please report bugs on https://github.com/matthiasmullie/minify/issues
 *
 * @author Matthias Mullie <minify@mullie.eu>
 *
 * @copyright Copyright (c) 2012, Matthias Mullie. All rights reserved.
 * @license MIT License
 */
abstract class Minify
{
    /**
     * Init the minify class - optionally, code may be passed along already.
     */
    public function __construct(/* $data = null, ... */)
    {

        if ($this instanceof JS) {
            $this->minifier = new Minifier(new Compressor\JS);
        } elseif ($this instanceof CSS) {
            $this->minifier = new Minifier(new Compressor\CSS);
        }

        // it's possible to add the source through the constructor as well ;)
        if (func_num_args()) {
            call_user_func_array(array($this, 'add'), func_get_args());
        }
    }

    /**
     * Add a file or straight-up code to be minified.
     *
     * @param string $data
     */
    public function add($data /* $data = null, ... */)
    {
        // bogus "usage" of parameter $data: scrutinizer warns this variable is
        // not used (we're using func_get_args instead to support overloading),
        // but it still needs to be defined because it makes no sense to have
        // this function without argument :)
        $args = array($data) + func_get_args();

        // this method can be overloaded
        foreach ($args as $data) {
            // redefine var
            $data = (string) $data;

            // check if the data is a file
            if (@file_exists($data) && is_file($data)) {
                $source = new Source\File($data);
            } else {
                $source = new Source\Code($data);
            }
            $this->minifier->addSource($source);
        }
    }

    /**
     * Minify the data & (optionally) saves it to a file.
     *
     * @param  string[optional] $path Path to write the data to.
     * @return string           The minified data.
     */
    public function minify($path = null)
    {
        if ($path) {
            $target = new Target\File($path);
            $this->minifier->addTarget($target);
        }

        $minified = '';
        $this->minifier->addTarget(new Target\Variable($minified));

        $this->minifier->execute();

        return $minified;
    }

    /**
     * Minify & gzip the data & (optionally) saves it to a file.
     *
     * @param  string[optional] $path Path to write the data to.
     * @param  int[optional]    $level Compression level, from 0 to 9.
     * @return string           The minified & gzipped data.
     */
    public function gzip($path = null, $level = 9)
    {
        // @todo

        $content = $this->execute($path);
        $content = gzencode($content, $level, FORCE_GZIP);

        // save to path
        if ($path !== null) {
            $this->save($content, $path);
        }

        return $content;
    }

    /**
     * Minify the data & write it to a CacheItemInterface object.
     *
     * @param  CacheItemInterface $item Cache item to write the data to.
     * @return CacheItemInterface       Cache item with the minifier data.
     */
    public function cache(CacheItemInterface $item)
    {
        // @todo

        $content = $this->execute();
        $item->set($content);

        return $item;
    }
}
