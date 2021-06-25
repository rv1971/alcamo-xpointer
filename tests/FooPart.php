<?php

namespace alcamo\xpointer;

class FooPart implements PartInterface
{
    public function process(
        array &$nsBindings,
        string $schemeData,
        \DOMDocument $doc
    ) {
        return $doc->documentElement->childNodes[$schemeData - 1];
    }
}
