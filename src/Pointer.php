<?php

namespace alcamo\xpointer;

use alcamo\exception\{SyntaxError, UnknownNamespacePrefix};
use alcamo\xml\{Syntax, XName};

/**
 * @brief XPointer
 *
 * @sa [XPointer Framework](https://www.w3.org/TR/xptr-framework/)
 *
 * @date Last reviewed 2021-06-24
 */
class Pointer implements PointerInterface
{
    /// Supported schemes
    public const SCHEME_MAP = [
        'xmlns'    => XmlnsPart::class,
        'xpointer' => XPointerPart::class
    ];

    /// Predefind namespace bindings
    public const INITIAL_NS_BINDINGS = [
        'xml' => 'http://www.w3.org/XML/1998/namespace'
    ];

    private $shorthand_; ///< ?string
    private $parts_;     ///< ?array of pairs of scheme name and data

    /**
     * @copybrief PointerInterface::newFromString()
     *
     * @warning Unescaped parentheses in scheme data are not supported, not
     * even when balanced.
     */
    public static function newFromString(string $fragment): PointerInterface
    {
        if (strpos($fragment, '(') === false) {
            return new static($fragment, null);
        } else {
            $pieces = preg_split(
                '/\(((?:\^\)|[^)])*)\)/',
                str_replace('%5E', '^', $fragment),
                -1,
                PREG_SPLIT_DELIM_CAPTURE
            );

            $parts = [];

            for ($i = 0; isset($pieces[$i]) && $pieces[$i] != ''; $i += 2) {
                $schemeName = ltrim($pieces[$i]);
                $schemeData = $pieces[$i + 1];

                if (!preg_match(Syntax::NAME_REGEXP, $schemeName)) {
                    /** @throw alcamo::exception::SyntaxError when
                     *  encountering a syntactically invalid scheme name. */
                    throw (new SyntaxError())->setMessageContext(
                        [
                            'inData' => $schemeName,
                            'extraMessage' => 'invalid scheme name'
                        ]
                    );
                }

                if (
                    preg_match(
                        '/(?<!\^)\^[^^()]/',
                        $schemeData,
                        $matches2,
                        PREG_OFFSET_CAPTURE
                    )
                ) {
                    /** @throw alcamo::exception::SyntaxError when a
                     *  circumflex is used neither to escape a parenthesis nor
                     *  another circumflex. */
                    throw (new SyntaxError())->setMessageContext(
                        [
                            'inData' => $schemeData,
                            'atOffset' => $matches2[0][1],
                            'extraMessage' => 'invalid use of circumflex'
                        ]
                    );
                }

                $schemeData = str_replace(
                    [ '^(', '^)', '^^' ],
                    [ '(', ')', '^' ],
                    $schemeData
                );

                $parts[] = [ $schemeName, $schemeData ];
            }

            return new static(null, $parts);
        }
    }

    /**
     * @param $shorthand Shorthand pointer, or `null`.
     *
     * @param $parts Array of pairs of scheme name and data, or `null`.
     */
    protected function __construct(?string $shorthand, ?array $parts)
    {
        $this->shorthand_ = $shorthand;
        $this->parts_ = $parts;
    }

    /// @copybrief PointerInterface::process()
    public function process(\DOMDocument $doc)
    {
        if (isset($this->shorthand_)) {
            return $doc->getElementById($this->shorthand_);
        }

        $nsBindings = static::INITIAL_NS_BINDINGS;

        foreach ($this->parts_ as $part) {
            [ $schemeName, $schemeData ] = $part;

            try {
                $schemeName =
                    (string)XName::newFromQNameAndMap($schemeName, $nsBindings);
            } catch (UnknownNamespacePrefix $e) {
                // gracefully skip unknown namespace prefixes
                continue;
            }

                // gracefully skip unsupported schemes
            if (isset(static::SCHEME_MAP[$schemeName])) {
                $class = static::SCHEME_MAP[$schemeName];

                $result =
                    (new $class())->process($nsBindings, $schemeData, $doc);
            }

            if (isset($result)) {
                return $result;
            }
        }
    }
}
