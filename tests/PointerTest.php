<?php

namespace alcamo\xpointer;

use PHPUnit\Framework\TestCase;
use alcamo\exception\SyntaxError;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'FooPointer.php';

class PointerTest extends TestCase
{
    /**
     * @dataProvider basicsProvider
     */
    public function testBasics(
        $doc,
        $pointerString,
        $expectedLocalName,
        $expectedContent
    ) {
        $pointer = FooPointer::newFromString($pointerString);

        $result = $pointer->process($doc);

        if ($result instanceof \DOMNode) {
            $this->assertSame($expectedLocalName, $result->localName);
            $this->assertSame($expectedContent, $result->textContent);
        } else {
            $this->assertSame($expectedLocalName, $result[0]->localName);
            $this->assertSame($expectedContent, $result[0]->textContent);
        }
    }

    public function basicsProvider()
    {
        $doc = new \DOMDocument();

        $doc->load(__DIR__ . DIRECTORY_SEPARATOR . 'foo.xml', LIBXML_NOBLANKS);

        return [
            'shorthand' => [ $doc, 'quux42', 'quux', 'consetetur' ],

            'xpointer' => [
                $doc,
                'xpointer(/*/*[2]/@content)',
                'content',
                'qux'
            ],

            'xmlns-xpointer' => [
                $doc,
                'xmlns(f=http://foo.example.org) xpointer(//f:baz)',
                'baz',
                'Lorem ipsum'
            ],

            'xmlns-xpointer-xmlns-xpointer' => [
                $doc,
                'xmlns(f=http://foo.example.org)'
                . 'xpointer(//f:bazzz)'
                . 'xmlns(b=http://bar.example.com)'
                . 'xpointer(//b:quux)',
                'quux',
                'consetetur'
            ],

            'unsupported' => [
                $doc,
                'xmlns(f=http://foo.example.org)'
                . 'foo(bar/baz/qux)'
                . 'f:foo(bar/baz/qux)'
                . 'xpointer(//f:bazzz)'
                . 'xmlns(b=http://bar.example.com)'
                . 'xpointer(//b:quux)',
                'quux',
                'consetetur'
            ],

            'escaped' => [
                $doc,
                'xpointer(//*[@name="^(^^^^^)"])',
                'baz',
                'takimata'
            ],

            'extension' => [
                $doc,
                'xmlns(f=http://foo.example.org)f:bar(2)',
                'baz',
                'sadipscing'
            ]
        ];
    }

    public function testExceptionSchemeName()
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            'Syntax error in "xml ns"; invalid scheme name'
        );

        FooPointer::newFromString('xml ns(x=http://example.com)');
    }

    public function testExceptionEscaping()
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            'Syntax error in "x=http://^example.com" at 9: "^example.c..."; '
            . 'invalid use of circumflex'
        );

        FooPointer::newFromString('xmlns(x=http://^example.com)');
    }
}
