<?php
namespace MatthiasMullie\Minify\Rule;

class ExtractStrings extends RegexRule implements Rule
{
    /**
     * @var string
     */
    protected $chars;

    /**
     * @param string $chars
     */
    public function __construct($chars = '\'"')
    {
        $this->chars = $chars;
    }

    /**
     * The \\ messiness explained:
     * * Don't count ' or " as end-of-string if it's escaped (has backslash
     * in front of it)
     * * Unless... that backslash itself is escaped (another leading slash),
     * in which case it's no longer escaping the ' or "
     * * So there can be either no backslash, or an even number
     * * multiply all of that times 4, to account for the escaping that has
     * to be done to pass the backslash into the PHP string without it being
     * considered as escape-char (times 2) and to get it in the regex,
     * escaped (times 2)
     *
     * {@inheritdoc}
     */
    protected function patterns()
    {
        return array(
            '/(['.$this->chars.'])(.*?((?<!\\\\)|\\\\\\\\+))\\1/s' => function (array $match) {
                return $match[0];
            }
        );
    }

    /**
     * Strings are a pattern we need to match in order to ignore potential
     * code-like content inside them, but we just want all of the content to
     * remain untouched.
     *
     * {@inheritdoc}
     */
    public function skip()
    {
        return true;
    }
}
