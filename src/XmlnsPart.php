<?php

namespace alcamo\xpointer;

/**
 * @brief XPointer xmlns() Scheme
 *
 * @sa [XPointer xmlns() Scheme](https://www.w3.org/TR/xptr-xmlns/)
 *
 * @date Last reviewed 2021-06-24
 */
class XmlnsPart implements PartInterface
{
    /**
     * @copybrief PartInterface::process()
     *
     * @warning The implementation does not enforce the constraints defined
     * in [Namespace Binding
     * Context](https://www.w3.org/TR/xptr-framework/#nsContext).
     */
    public function process(
        array &$nsBindings,
        string $schemeData,
        \DOMDocument $doc
    ) {
        $a = explode('=', $schemeData, 2);

        $nsBindings[rtrim($a[0])] = ltrim($a[1]);
    }
}
