<?php

class JSTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test minifier rules, provided by dataProvider
     *
     * @test
     * @dataProvider dataProvider
     */
    public function minify($input, $expected)
    {
        $result = '';

        $compressor = new \MatthiasMullie\Minify\Compressor\JS();
        $minifier = new \MatthiasMullie\Minify\Minifier($compressor);
        $minifier->addSource(new \MatthiasMullie\Minify\Source\Code($input));
        $minifier->addTarget(new \MatthiasMullie\Minify\Target\Variable($result));
        $minifier->execute();

        $this->assertEquals($expected, $result);
    }

    /**
     * @return array [input, expected result]
     */
    public function dataProvider()
    {
        $tests = array();

        // escaped quotes should not terminate string
        $tests[] = array(
            'alert("Escaped quote which is same as string quotes: \"; should not match")',
            'alert("Escaped quote which is same as string quotes: \"; should not match")',
        );

        // backtick string (allow string interpolation)
        $tests[] = array(
            'var str=`Hi, ${name}`',
            'var str=`Hi, ${name}`',
        );

        // regex delimiters need to be treated as strings
        // (two forward slashes could look like a comment)
        $tests[] = array(
            '/abc\/def\//.test("abc")',
            '/abc\/def\//.test("abc")',
        );
        $tests[] = array(
            'var a = /abc\/def\//.test("abc")',
            'var a=/abc\/def\//.test("abc")',
        );

        // don't confuse multiple slashes for regexes
        $tests[] = array(
            'a = b / c; d = e / f',
            'a=b/c;d=e/f',
        );

        // mixture of quotes starting in comment/regex, to make sure strings are
        // matched correctly, not inside comment/regex.
        $tests[] = array(
            '/abc"def/.test("abc")',
            '/abc"def/.test("abc")',
        );
        $tests[] = array(
            '/* Bogus " */var test="test";',
            'var test="test"',
        );

        // replace comments
        $tests[] = array(
            '/* This is a JS comment */',
            '',
        );

        return $tests;
    }
}
