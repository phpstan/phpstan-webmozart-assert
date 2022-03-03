<?php declare(strict_types=1);

namespace PHPStan\Type\WebMozartAssert;

use DateTimeImmutable;
use stdClass;
use Webmozart\Assert\Assert;

class TypeTest
{
	public function string($a, $b): void
	{
		Assert::string($a);
		\PHPStan\Testing\assertType('string', $a);

		Assert::nullOrString($b);
		\PHPStan\Testing\assertType('string|null', $b);
	}

	public function stringNotEmpty($a, $b): void
	{
		Assert::stringNotEmpty($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);

		Assert::nullOrStringNotEmpty($b);
		\PHPStan\Testing\assertType('non-empty-string|null', $b);
	}

	public function integer($a, $b): void
	{
		Assert::integer($a);
		\PHPStan\Testing\assertType('int', $a);

		Assert::nullOrInteger($b);
		\PHPStan\Testing\assertType('int|null', $b);
	}

	/**
	 * @param numeric-string $e
	 */
	public function integerish($a, $b, int $c, float $d, string $e, string $f): void
	{
		Assert::integerish($a);
		\PHPStan\Testing\assertType('float|int|numeric-string', $a);

		Assert::nullOrIntegerish($b);
		\PHPStan\Testing\assertType('float|int|numeric-string|null', $b);

		Assert::integerish($c);
		\PHPStan\Testing\assertType('int', $c);

		Assert::integerish($d);
		\PHPStan\Testing\assertType('float', $d);

		Assert::integerish($e);
		\PHPStan\Testing\assertType('numeric-string', $e);

		Assert::integerish($f);
		\PHPStan\Testing\assertType('numeric-string', $f);
	}

	public function positiveInteger($a, $b, $c): void
	{
		Assert::positiveInteger($a);
		\PHPStan\Testing\assertType('int<1, max>', $a);

		/** @var -1 $b */
		Assert::positiveInteger($b);
		\PHPStan\Testing\assertType('*NEVER*', $b);

		Assert::nullOrPositiveInteger($c);
		\PHPStan\Testing\assertType('int<1, max>|null', $c);
	}

	public function float($a, $b): void
	{
		Assert::float($a);
		\PHPStan\Testing\assertType('float', $a);

		Assert::nullOrFloat($b);
		\PHPStan\Testing\assertType('float|null', $b);
	}

	public function numeric($a, $b): void
	{
		Assert::numeric($a);
		\PHPStan\Testing\assertType('float|int|numeric-string', $a);

		Assert::nullOrNumeric($b);
		\PHPStan\Testing\assertType('float|int|numeric-string|null', $b);
	}

	public function natural($a, $b, $c): void
	{
		Assert::natural($a);
		\PHPStan\Testing\assertType('int<0, max>', $a);

		/** @var -1 $b */
		Assert::natural($b);
		\PHPStan\Testing\assertType('*NEVER*', $b);

		Assert::nullOrNatural($c);
		\PHPStan\Testing\assertType('int<0, max>|null', $c);
	}

	public function boolean($a, $b): void
	{
		Assert::boolean($a);
		\PHPStan\Testing\assertType('bool', $a);

		Assert::nullOrBoolean($b);
		\PHPStan\Testing\assertType('bool|null', $b);
	}

	public function scalar($a, $b): void
	{
		Assert::scalar($a);
		\PHPStan\Testing\assertType('bool|float|int|string', $a);

		Assert::nullOrScalar($b);
		\PHPStan\Testing\assertType('bool|float|int|string|null', $b);
	}

	public function object($a, $b): void
	{
		Assert::object($a);
		\PHPStan\Testing\assertType('object', $a);

		Assert::nullOrObject($b);
		\PHPStan\Testing\assertType('object|null', $b);
	}

	public function resource($a, $b): void
	{
		Assert::resource($a);
		\PHPStan\Testing\assertType('resource', $a);

		Assert::nullOrResource($b);
		\PHPStan\Testing\assertType('resource|null', $b);
	}

	public function isCallable($a, $b): void
	{
		Assert::isCallable($a);
		\PHPStan\Testing\assertType('callable(): mixed', $a);

		Assert::nullOrIsCallable($b);
		\PHPStan\Testing\assertType('(callable(): mixed)|null', $b);
	}

	public function isArray($a, $b): void
	{
		Assert::isArray($a);
		\PHPStan\Testing\assertType('array', $a);

		Assert::nullOrIsArray($b);
		\PHPStan\Testing\assertType('array|null', $b);
	}

	public function isTraversable($a, $b): void
	{
		Assert::isTraversable($a);
		\PHPStan\Testing\assertType('array|Traversable', $a);

		Assert::nullOrIsTraversable($b);
		\PHPStan\Testing\assertType('array|Traversable|null', $b);
	}

	public function isIterable($a, $b): void
	{
		Assert::isIterable($a);
		\PHPStan\Testing\assertType('array|Traversable', $a);

		Assert::nullOrIsIterable($b);
		\PHPStan\Testing\assertType('array|Traversable|null', $b);
	}

	public function isCountable($a, $b): void
	{
		Assert::isCountable($a);
		\PHPStan\Testing\assertType('array|Countable', $a);

		Assert::nullOrIsCountable($b);
		\PHPStan\Testing\assertType('array|Countable|null', $b);
	}

	public function isInstanceOf($a, $b, $c, $d): void
	{
		Assert::isInstanceOf($a, self::class);
		\PHPStan\Testing\assertType('PHPStan\Type\WebMozartAssert\TypeTest', $a);

		Assert::nullOrIsInstanceOf($b, self::class);
		\PHPStan\Testing\assertType('PHPStan\Type\WebMozartAssert\TypeTest|null', $b);

		Assert::isInstanceOf($c, new stdClass());
		\PHPStan\Testing\assertType('stdClass', $c);

		Assert::isInstanceOf($d, 17);
		\PHPStan\Testing\assertType('mixed', $d);
	}

	public function isInstanceOfAny($a, $b, $c, $d, $e, $f, $g): void
	{
		Assert::isInstanceOfAny($a, [self::class, new stdClass()]);
		\PHPStan\Testing\assertType('PHPStan\Type\WebMozartAssert\TypeTest|stdClass', $a);

		Assert::isInstanceOfAny($b, [new stdClass()]);
		\PHPStan\Testing\assertType('stdClass', $b);

		Assert::isInstanceOfAny($c, []);
		\PHPStan\Testing\assertType('mixed', $c);

		Assert::isInstanceOfAny($d, 'foo');
		\PHPStan\Testing\assertType('mixed', $d);

		Assert::isInstanceOfAny($e, [17]);
		\PHPStan\Testing\assertType('mixed', $e);

		Assert::isInstanceOfAny($f, [17, self::class]);
		\PHPStan\Testing\assertType('PHPStan\Type\WebMozartAssert\TypeTest', $f);

		Assert::nullOrIsInstanceOfAny($g, [self::class, new stdClass()]);
		\PHPStan\Testing\assertType('PHPStan\Type\WebMozartAssert\TypeTest|stdClass|null', $g);
	}

	/**
	 * @param Foo|Bar $a
	 * @param Foo|stdClass $b
	 * @param Foo|Bar $c
	 */
	public function notInstanceOf($a, $b, $c): void
	{
		Assert::notInstanceOf($a, Bar::class);
		\PHPStan\Testing\assertType('PHPStan\Type\WebMozartAssert\Foo', $a);

		Assert::notInstanceOf($b, new stdClass());
		\PHPStan\Testing\assertType('PHPStan\Type\WebMozartAssert\Foo', $b);

		Assert::notInstanceOf($c, 17);
		\PHPStan\Testing\assertType('PHPStan\Type\WebMozartAssert\Bar|PHPStan\Type\WebMozartAssert\Foo', $c);
	}

	public function isAOf($a, $b, string $c): void
	{
		Assert::isAOf($a, stdClass::class);
		\PHPStan\Testing\assertType('stdClass', $a);

		Assert::isAOf(Foo::class, stdClass::class);
		\PHPStan\Testing\assertType('*NEVER*', Foo::class);

		Assert::isAOf(Bar::class, stdClass::class);
		\PHPStan\Testing\assertType('\'PHPStan\\\Type\\\WebMozartAssert\\\Bar\'', Bar::class);

		Assert::nullOrIsAOf($b, stdClass::class);
		\PHPStan\Testing\assertType('stdClass|null', $b);

		Assert::isAOf($c, stdClass::class);
		\PHPStan\Testing\assertType('class-string<stdClass>', $c);
	}

	public function isAnyOf($a, $b): void
	{
		Assert::isAnyOf($a, [DateTimeImmutable::class, stdClass::class]);
		\PHPStan\Testing\assertTyp0e('DateTimeImmutable|stdClass', $a);

		Assert::isAnyOf($b, []);
		\PHPStan\Testing\assertType('mixed', $b);

		Assert::isAnyOf(Foo::class, [stdClass::class, Bar::class]);
		\PHPStan\Testing\assertType('*NEVER*', Foo::class);

		Assert::isAnyOf(Bar::class, [stdClass::class, Foo::class]);
		\PHPStan\Testing\assertType('\'PHPStan\\\Type\\\WebMozartAssert\\\Bar\'', Bar::class);
	}

	/**
	 * @param DateTimeImmutable|stdClass $a
	 * @param class-string<DateTimeImmutable>|class-string<stdClass> $b
	 * @param DateTimeImmutable|stdClass|null $c
	 */
	public function isNotA(object $a, string $b, ?object $c): void
	{
		Assert::isNotA($a, stdClass::class);
		\PHPStan\Testing\assertType('DateTimeImmutable', $a);

		Assert::isNotA($b, stdClass::class);
		\PHPStan\Testing\assertType('class-string<DateTimeImmutable>', $b);

		Assert::nullOrIsNotA($c, stdClass::class);
		\PHPStan\Testing\assertType('DateTimeImmutable|null', $c);

		Assert::isNotA(Foo::class, stdClass::class);
		\PHPStan\Testing\assertType('\'PHPStan\\\Type\\\WebMozartAssert\\\Foo\'', Foo::class);

		Assert::isNotA(Bar::class, stdClass::class);
		\PHPStan\Testing\assertType('*NEVER*', Bar::class);
	}

	public function isArrayAccessible($a, $b): void
	{
		Assert::isArrayAccessible($a);
		\PHPStan\Testing\assertType('array|ArrayAccess', $a);

		Assert::nullOrIsArrayAccessible($b);
		\PHPStan\Testing\assertType('array|ArrayAccess|null', $b);
	}
}

class Foo {}

class Bar extends stdClass {}
