<?php

namespace alcamo\xpointer;

/**
 * @brief XPointer part interface
 *
 * @sa [XPointer Framework: Pointer part](https://www.w3.org/TR/xptr-framework/#dt-pointerpart)
 *
 * @date Last reviewed 2025-10-06
 */
interface PartInterface
{
    /**
     * @brief Process a pointer on a DOM document
     *
     * @param $nsBindings Namespace bindings. May be modified by this method.
     *
     * @param $schemeData See [XPointer
     * Syntax](https://www.w3.org/TR/xptr-framework/#syntax)
     *
     * @param $doc Document with respect to which the pointer is evaluated.
     *
     * @return `null` to continue processing with the next part. Anything else
     * will be the final result of XPointer processing.
     */
    public function process(
        array &$nsBindings,
        string $schemeData,
        \DOMDocument $doc
    );
}
