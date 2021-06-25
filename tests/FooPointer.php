<?php

namespace alcamo\xpointer;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'FooPart.php';

class FooPointer extends Pointer
{
    public const SCHEME_MAP = parent::SCHEME_MAP + [
        'http://foo.example.org bar' => FooPart::class
    ];
}
