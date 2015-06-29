<?php
namespace MatthiasMullie\Minify\Compressor;
use MatthiasMullie\Minify\Rule\ExtractStrings;
use MatthiasMullie\Minify\Rule\ExtractRegex;
use MatthiasMullie\Minify\Rule\StripComments;

/**
 * Please report bugs on https://github.com/matthiasmullie/minify/issues
 *
 * @author Matthias Mullie <minify@mullie.eu>
 *
 * @copyright Copyright (c) 2012, Matthias Mullie. All rights reserved.
 * @license MIT License
 */
class JS extends Compressor
{
    public function __construct()
    {
        $dir = __DIR__.'/../../data/js/';
        $options = FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES;
        $operators = array_merge(
            file($dir.'operators_before.txt', $options),
            file($dir.'operators_after.txt', $options)
        );

        $this->rules[] = new ExtractStrings('\'"`');
        $this->rules[] = new ExtractRegex($operators);
        $this->rules[] = new StripComments();
    }
}
