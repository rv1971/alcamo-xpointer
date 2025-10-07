<?php

namespace alcamo\xpointer;

/**
 * @brief XPointer xpointer() Scheme
 *
 * @warning Extensions to XPath 1.0 are not supported.
 *
 * @sa [XPointer xpointer() Scheme](https://www.w3.org/TR/xptr-xpointer/)
 *
 * @date Last reviewed 2025-10-07
 */
class XpointerPart implements PartInterface
{
    /**
     * @copybrief PartInterface::process()
     *
     * @return DOMNodeList or `null`
     */
    public function process(
        array &$nsBindings,
        string $schemeData,
        \DOMDocument $doc
    ) {
        $xPath = new \DOMXPath($doc);

        foreach ($nsBindings as $prefix => $nsName) {
            $xPath->registerNamespace($prefix, $nsName);
        }

        $result = $xPath->evaluate($schemeData);

        return $result === false || !isset($result[0]) ? null : $result;
    }
}
