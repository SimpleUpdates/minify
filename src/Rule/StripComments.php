<?php
namespace MatthiasMullie\Minify\Rule;

class StripComments extends RegexRule implements Rule
{
    /**
     * {@inheritdoc}
     */
    protected function patterns()
    {
        $callback = function () {
            return '';
        };

        // it's a regex if we can find an opening (not preceded by variable,
        // value or similar) & (non-escaped) closing /,
        return array(
            // single-line comments
            '/\/\/.*$/m' => $callback,
            // multi-line comments
            '/\/\*.*?\*\//s' => $callback,
        );
    }
}
