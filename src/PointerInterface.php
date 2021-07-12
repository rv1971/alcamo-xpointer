<?php

namespace alcamo\xpointer;

/**
 * @namespace alcamo::xpointer
 *
 * @brief Implementation of XPointer framework with xmlns() and xpointer()
 * schemes
 */

/**
 * @brief XPointer interface
 *
 * @sa [XPointer Framework](https://www.w3.org/TR/xptr-framework/)
 *
 * @date Last reviewed 2021-06-24
 */
interface PointerInterface
{
    /// Create from URL fragment string
    public static function newFromString(string $fragment): self;

    /// Process a pointer on a DOM document
    public function process(\DOMDocument $doc);
}
