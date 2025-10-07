# Usage example

~~~
use alcamo\xpointer\Pointer;

$doc = new \DOMDocument();
~~~

... load something into the document ...

~~~
$urlFragment = 'xmlns(f=http://foo.example.org) xpointer(/*/f:bar[42])';

$pointer = new Pointer($urlFragment);
~~~

The following evaluates the XPath `/*/f:bar[42]` on the document,
where the prefix `f` is registered as the namespace
`http://foo.example.org`:

~~~
$bar = $pointer->process($doc);
~~~


# Supplied classes and interfaces

## Interface `PointerInterface`

Simple interface for classes that implement a
[Pointer](https://www.w3.org/TR/xptr-framework/#dt-pointer). Requires
a static factory method to create a Pointer from an URL fragment
string and a method to process the pointer on a DOM document.

## Class `Pointer`

Implementation of `PointerInterface` supporting [Shorthand Pointers](https://www.w3.org/TR/xptr-framework/#shorthand) as well as the [XPointer xmlns()
Scheme](https://www.w3.org/TR/xptr-xmlns/) and [XPointer xpointer()
Scheme](https://www.w3.org/TR/xptr-xpointer/).

## Interface `PartInterface`

Simple interface for classes that implement a [Pointer
part](https://www.w3.org/TR/xptr-framework/#dt-pointerpart). Requires
just a `process()` method to process the [Scheme
data](https://www.w3.org/TR/xptr-framework/#syntax). Processing of
parts stops as soon as this method return a non-`null` value.

## Class `XmlnsPart`

Implementation of `PartInterface` for the [XPointer xmlns()
Scheme](https://www.w3.org/TR/xptr-xmlns/). The `process()` method
modifies the namespace bindings as described in the specification.

## Class `XpointerPart`

Implementation of `PartInterface` for the [XPointer xpointer()
Scheme](https://www.w3.org/TR/xptr-xpointer/). The `process()` method
returns the result of XPath evaluation, if successful.


# Extending this package

To add other XPointer schemes:
1. Write a new pointer part class that implements `PartInterface`.
2. Write a class derived from `Pointer` and add your new pointer part
   class to the class constant `SCHEME_MAP`.
