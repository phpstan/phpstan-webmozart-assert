<?php

use Webmozart\Assert\Assert;
use function PHPStan\Testing\assertType;

function assertInstanceOfWithString($value, string $className): void
{
	Assert::isInstanceOf($value, $className);
	assertType('object', $value);
}

/**
 * @param class-string $className
 */
function assertInstanceOfWithClassString($value, string $className): void
{
	Assert::isInstanceOf($value, $className);
	assertType('object', $value);
}

/**
 * @param class-string<\Bug183> $className
 */
function assertInstanceOfWithGenericClassString($value, string $className): void
{
	Assert::isInstanceOf($value, $className);
	assertType('Bug183', $value);
}

/**
 * @param class-string<\Bug183Bar<\Bug183>> $className
 */
function assertInstanceOfWithGenericClassStringReferencingGenericClass($value, string $className): void
{
	Assert::isInstanceOf($value, $className);
	assertType('Bug183Bar', $value);
}

/**
 * @param class-string<\Bug183|\Bug183Foo> $className
 */
function assertInstanceOfWithGenericUnionClassString($value, string $className): void
{
	Assert::isInstanceOf($value, $className);
	assertType('Bug183|Bug183Foo', $value);
}


interface Bug183
{
}

interface Bug183Foo
{
}

/**
 * @template T of object
 */
interface Bug183Bar
{
}
