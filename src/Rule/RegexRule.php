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
abstract class RegexRule implements Rule
{
    /*
     * @todo old minddump!
     * Compressor creates Rule objects & treats those as existing registered patterns.
     * find() locates the first next occurrence of this pattern (=rule)
     * replace() replaces that occurrence with a <Rule:ClassName:1> (where 1 is id)
     *           and stores original content in $this->extracted[1]
     * commit() replaces <Rule:ClassName:1> with minified content
     * rollback() (or __destruct) replaces <Rule:ClassName:1> with original content
     * @todo how to figure out positions (for sourcemap)?
     */

    /**
     * @return \Closure[] Array where key is regex & value is replacement callback
     */
    abstract protected function patterns();

    /**
     * @param string $code
     * @param int $offset
     * @return string
     * @throws Exception
     */
    public function find($code, $offset = 0)
    {
        $success = preg_match($this->pattern(), $code, $match, 0, $offset);

        if ($success === false) {
            throw new Exception('An error occurred while executing '.__CLASS__.' rule regex.');
        } elseif ($success === 0) {
            throw new Exception('Nothing matched for '.__CLASS__.' rule regex.');
        }


        // @todo: fuck, with this approach, I can't ignore entire regexes, only rules... :(
        $patterns = $this->patterns();
        foreach ($patterns as $pattern => $callback) {
            $success = preg_match($pattern, $code, $match, 0, $offset);
            if ($success === false) {
                throw new Exception('An error occurred while executing '.__CLASS__.' rule regex.');
            } elseif ($success === 0) {
                // @todo: check if first...
            }
        }


        return new Match(
            /* $offset= */ mb_strpos($code, $match[0]),
            /* $length= */ mb_strlen($match[0]),
            /* $data= */ $match
        );
    }

    /**
     * @param Match $match
     * @return string
     */
    public function replacement(Match $match)
    {
        /** @var \Closure $callback */
        $callback = $match->data['callback'];
        /** @var array $data */
        $data = $match->data['match'];

        // @todo: don't need the extraction, just return the exact same result (we'll use "skip")
        return $callback($data);
    }

    /**
     * @return bool
     */
    public function skip()
    {
        // @todo: phpdoc what it is - and will it be used?
        return false;
    }
}
