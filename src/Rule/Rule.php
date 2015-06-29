<?php
namespace MatthiasMullie\Minify\Rule;

/**
 * Please report bugs on https://github.com/matthiasmullie/minify/issues
 *
 * @author Matthias Mullie <minify@mullie.eu>
 *
 * @copyright Copyright (c) 2012, Matthias Mullie. All rights reserved.
 * @license MIT License
 */
interface Rule
{
    /**
     * @param string $code
     * @param int $offset
     * @return Match
     */
    public function find($code, $offset = 0);

    /**
     * @param Match $match
     * @return string
     */
    public function replacement(Match $match);
}
