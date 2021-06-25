<?php

namespace alcamo\xpointer;

/**
 * @brief XPointer part interface
 *
 * @sa [XPointer Framework](https://www.w3.org/TR/xptr-framework/)
 *
 * @date Last reviewed 2021-06-24
 */
interface PartInterface
{
    /**
     * @brief Process a pointer on a DOM document
     *
     * @param $nsBindings Namespace bindings; may be modified by this method.
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
