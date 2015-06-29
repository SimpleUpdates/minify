<?php
namespace MatthiasMullie\Minify\Compressor;

use MatthiasMullie\Minify\Rule;

/**
 * Please report bugs on https://github.com/matthiasmullie/minify/issues
 *
 * @author Matthias Mullie <minify@mullie.eu>
 *
 * @copyright Copyright (c) 2012, Matthias Mullie. All rights reserved.
 * @license MIT License
 */
abstract class Compressor // @todo: make this an interface?
{
    /**
     * @var Rule\Rule[]
     */
    protected $rules = array();

    // @todo: what input/output?
    public function minify($code)
    {
        // @todo: I'll probably want to move (most of) this code into Minifier.php? IDK!

        $rules = $this->rules;
        /** @var Rule\Match[] $matches */
        $matches = array();
        $offset = 0;

        foreach ($rules as $i => $rule) {
            if (isset($matches[$i])) {
                // we've already executed this rule & we're not yet at the
                // point where it matches, so that one is still valid
                continue;
            }

            try {
                $matches[$i] = $rule->find($code, $offset);
            } catch (Rule\Exception $e) {
                // if the pattern couldn't be matched, there's no point in
                // executing it again in later runs on this same content
                unset($rules[$i]);
            }
        }

        // sort matches based on which comes first, then get the first rule & match
        uasort($matches, function(Rule\Match $a, Rule\Match $b) {
            return $a->offset < $b->offset ? -1 : $a->offset === $b->offset ? 0 : 1;
        });
        reset($matches); // @todo: may not need this line?
        $rule = $rules[key($matches)];
        $match = array_shift($matches);

        $replacement = $rule->replacement($match);
        $code = mb_substr($code, 0, $match->offset) . $replacement . mb_substr($code, $match->offset + $match->length);

        // @todo: remove all < end
        $end = $match->offset + $match->length;
        foreach ($matches as $i => $match) {
            if ($match->offset < $end) {
                // @todo: $match->offset is invalid now; it'll need to decrease (replacement size is likely lower)
                unset($matches[$i]);
            }
        }

        // @todo: update $offset

        return $code;


        $processed = '';
        $positions = array_fill(0, count($this->patterns), -1);
        $matches = array();

        while ($content) {
            // find first match for all patterns
            foreach ($this->patterns as $i => $pattern) {
                list($pattern, $replacement) = $pattern;

                // no need to re-run matches that are still in the part of the
                // content that hasn't been processed
                if ($positions[$i] >= 0) {
                    continue;
                }

                $match = null;
                if (preg_match($pattern, $content, $match)) {
                    $matches[$i] = $match;

                    // we'll store the match position as well; that way, we
                    // don't have to redo all preg_matches after changing only
                    // the first (we'll still know where those others are)
                    $positions[$i] = strpos($content, $match[0]);
                } else {
                    // if the pattern couldn't be matched, there's no point in
                    // executing it again in later runs on this same content;
                    // ignore this one until we reach end of content
                    unset($matches[$i]);
                    $positions[$i] = strlen($content);
                }
            }

            // no more matches to find: everything's been processed, break out
            if (!$matches) {
                $processed .= $content;
                break;
            }

            // see which of the patterns actually found the first thing (we'll
            // only want to execute that one, since we're unsure if what the
            // other found was not inside what the first found)
            $discardLength = min($positions);
            $firstPattern = array_search($discardLength, $positions);
            $match = $matches[$firstPattern][0];

            // execute the pattern that matches earliest in the content string
            list($pattern, $replacement) = $this->patterns[$firstPattern];
            $replacement = $this->replacePattern($pattern, $replacement, $content);

            // figure out which part of the string was unmatched; that's the
            // part we'll execute the patterns on again next
            $content = substr($content, $discardLength);
            $unmatched = (string) substr($content, strpos($content, $match) + strlen($match));

            // move the replaced part to $processed and prepare $content to
            // again match batch of patterns against
            $processed .= substr($replacement, 0, strlen($replacement) - strlen($unmatched));
            $content = $unmatched;

            // first match has been replaced & that content is to be left alone,
            // the next matches will start after this replacement, so we should
            // fix their offsets
            foreach ($positions as $i => $position) {
                $positions[$i] -= $discardLength + strlen($match);
            }
        }

        return $processed;
    }
}
