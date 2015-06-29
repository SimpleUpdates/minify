<?php
namespace MatthiasMullie\Minify\Rule;

class ExtractRegex extends RegexRule implements Rule
{
    /**
     * @var string[]
     */
    protected $operators;

    /**
     * @param string[] $operators Allowed operators before regex (slashes
     *  between variables/values is likely division, slashes after operator
     *  is likely regex)
     */
    public function __construct($operators = array())
    {
        $this->operators = $operators;
    }

    /**
     * JS can have /-delimited regular expressions, like: /ab+c/.match(string)
     *
     * The content inside the regex can contain characters that may be confused
     * for JS code: e.g. it could contain whitespace it needs to match & we
     * don't want to strip whitespace in there.
     *
     * The regexes can be pretty simple: we don't have to care about comments,
     * (which also use slashes) because another rule will have stripped those
     * already.
     *
     * {@inheritdoc}
     */
    protected function patterns()
    {
        $callback = function ($match) {
            return $match[0];
        };

        // it's a regex if we can find an opening (not preceded by variable,
        // value or similar) & (non-escaped) closing /,
        return array(
            '/^\s*+\K\/(.*?(?<!\\\\)(\\\\\\\\)*+)\//' => $callback,
            '/(?:'.implode('|', $this->operators).')\s*+\K\/(.*?(?<!\\\\)(\\\\\\\\)*+)\//' => $callback,
        );
    }

    /**
     * Regular expressions are a pattern we need to match in order to ignore
     * potential code-like content inside them, but we just want all of the
     * content to remain untouched.
     *
     * {@inheritdoc}
     */
    public function skip()
    {
        return true;
    }
}
