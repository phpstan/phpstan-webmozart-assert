<?php declare(strict_types=1);

namespace PHPStan\Type\WebMozartAssert;

use DateTimeImmutable;
use stdClass;
use Webmozart\Assert\Assert;
use function PHPStan\Testing\assertType;

class TypeTest
{
	public function string($a, $b): void
	{
		Assert::string($a);
		assertType('string', $a);

		Assert::nullOrString($b);
		assertType('string|null', $b);
	}

	public function stringNotEmpty($a, $b): void
	{
		Assert::stringNotEmpty($a);
		assertType('non-empty-string', $a);

		Assert::nullOrStringNotEmpty($b);
		assertType('non-empty-string|null', $b);
	}

	public function integer($a, $b): void
	{
		Assert::integer($a);
		assertType('int', $a);

		Assert::nullOrInteger($b);
		assertType('int|null', $b);
	}

	/**
	 * @param numeric-string $e
	 */
	public function integerish($a, $b, int $c, float $d, string $e, string $f): void
	{
		Assert::integerish($a);
		assertType('float|int|numeric-string', $a);

		Assert::nullOrIntegerish($b);
		assertType('float|int|numeric-string|null', $b);

		Assert::integerish($c);
		assertType('int', $c);

		Assert::integerish($d);
		assertType('float', $d);

		Assert::integerish($e);
		assertType('numeric-string', $e);

		Assert::integerish($f);
		assertType('numeric-string', $f);
	}

	public function positiveInteger($a, $b, $c): void
	{
		Assert::positiveInteger($a);
		assertType('int<1, max>', $a);

		/** @var -1 $b */
		Assert::positiveInteger($b);
		assertType('*NEVER*', $b);

		Assert::nullOrPositiveInteger($c);
		assertType('int<1, max>|null', $c);
	}

	public function float($a, $b): void
	{
		Assert::float($a);
		assertType('float', $a);

		Assert::nullOrFloat($b);
		assertType('float|null', $b);
	}

	public function numeric($a, $b): void
	{
		Assert::numeric($a);
		assertType('float|int|numeric-string', $a);

		Assert::nullOrNumeric($b);
		assertType('float|int|numeric-string|null', $b);
	}

	public function natural($a, $b, $c): void
	{
		Assert::natural($a);
		assertType('int<0, max>', $a);

		/** @var -1 $b */
		Assert::natural($b);
		assertType('*NEVER*', $b);

		Assert::nullOrNatural($c);
		assertType('int<0, max>|null', $c);
	}

	public function boolean($a, $b): void
	{
		Assert::boolean($a);
		assertType('bool', $a);

		Assert::nullOrBoolean($b);
		assertType('bool|null', $b);
	}

	public function scalar($a, $b): void
	{
		Assert::scalar($a);
		assertType('bool|float|int|string', $a);

		Assert::nullOrScalar($b);
		assertType('bool|float|int|string|null', $b);
	}

	public function object($a, $b): void
	{
		Assert::object($a);
		assertType('object', $a);

		Assert::nullOrObject($b);
		assertType('object|null', $b);
	}

	public function resource($a, $b): void
	{
		Assert::resource($a);
		assertType('resource', $a);

		Assert::nullOrResource($b);
		assertType('resource|null', $b);
	}

	public function isCallable($a, $b): void
	{
		Assert::isCallable($a);
		assertType('callable(): mixed', $a);

		Assert::nullOrIsCallable($b);
		assertType('(callable(): mixed)|null', $b);
	}

	public function isArray($a, $b): void
	{
		Assert::isArray($a);
		assertType('array', $a);

		Assert::nullOrIsArray($b);
		assertType('array|null', $b);
	}

	public function isTraversable($a, $b): void
	{
		Assert::isTraversable($a);
		assertType('array|Traversable', $a);

		Assert::nullOrIsTraversable($b);
		assertType('array|Traversable|null', $b);
	}

	public function isIterable($a, $b): void
	{
		Assert::isIterable($a);
		assertType('array|Traversable', $a);

		Assert::nullOrIsIterable($b);
		assertType('array|Traversable|null', $b);
	}

	public function isCountable($a, $b): void
	{
		Assert::isCountable($a);
		assertType('array|Countable', $a);

		Assert::nullOrIsCountable($b);
		assertType('array|Countable|null', $b);
	}

	public function isInstanceOf($a, $b, $c, $d): void
	{
		Assert::isInstanceOf($a, self::class);
		assertType('PHPStan\Type\WebMozartAssert\TypeTest', $a);

		Assert::nullOrIsInstanceOf($b, self::class);
		assertType('PHPStan\Type\WebMozartAssert\TypeTest|null', $b);

		Assert::isInstanceOf($c, new stdClass());
		assertType('stdClass', $c);

		Assert::isInstanceOf($d, 17);
		assertType('object', $d);
	}

	public function isInstanceOfAny($a, $b, $c, $d, $e, $f, $g): void
	{
		Assert::isInstanceOfAny($a, [self::class, new stdClass()]);
		assertType('PHPStan\Type\WebMozartAssert\TypeTest|stdClass', $a);

		Assert::isInstanceOfAny($b, [new stdClass()]);
		assertType('stdClass', $b);

		Assert::isInstanceOfAny($c, []);
		assertType('mixed', $c);

		Assert::isInstanceOfAny($d, 'foo');
		assertType('mixed', $d);

		Assert::isInstanceOfAny($e, [17]);
		assertType('object', $e);

		Assert::isInstanceOfAny($f, [17, self::class]);
		assertType('object', $f);

		Assert::nullOrIsInstanceOfAny($g, [self::class, new stdClass()]);
		assertType('PHPStan\Type\WebMozartAssert\TypeTest|stdClass|null', $g);
	}

	/**
	 * @param Foo|Bar $a
	 * @param Foo|stdClass $b
	 * @param Foo|Bar $c
	 */
	public function notInstanceOf($a, $b, $c): void
	{
		Assert::notInstanceOf($a, Bar::class);
		assertType('PHPStan\Type\WebMozartAssert\Foo', $a);

		Assert::notInstanceOf($b, new stdClass());
		assertType('PHPStan\Type\WebMozartAssert\Foo', $b);

		Assert::notInstanceOf($c, 17);
		assertType('PHPStan\Type\WebMozartAssert\Bar|PHPStan\Type\WebMozartAssert\Foo', $c);
	}

	public function isAOf($a, $b, string $c): void
	{
		Assert::isAOf($a, stdClass::class);
		assertType('stdClass', $a);

		Assert::isAOf(Foo::class, stdClass::class);
		assertType('*NEVER*', Foo::class);

		Assert::isAOf(Bar::class, stdClass::class);
		assertType('\'PHPStan\\\Type\\\WebMozartAssert\\\Bar\'', Bar::class);

		Assert::nullOrIsAOf($b, stdClass::class);
		assertType('stdClass|null', $b);

		Assert::isAOf($c, stdClass::class);
		assertType('class-string<stdClass>', $c);
	}

	public function isAnyOf($a, $b): void
	{
		Assert::isAnyOf($a, [DateTimeImmutable::class, stdClass::class]);
		assertType('DateTimeImmutable|stdClass', $a);

		Assert::isAnyOf($b, []);
		assertType('mixed', $b);

		Assert::isAnyOf(Foo::class, [stdClass::class, Bar::class]);
		assertType('*NEVER*', Foo::class);

		Assert::isAnyOf(Bar::class, [stdClass::class, Foo::class]);
		assertType('\'PHPStan\\\Type\\\WebMozartAssert\\\Bar\'', Bar::class);
	}

	/**
	 * @param DateTimeImmutable|stdClass $a
	 * @param class-string<DateTimeImmutable>|class-string<stdClass> $b
	 * @param DateTimeImmutable|stdClass|null $c
	 */
	public function isNotA(object $a, string $b, ?object $c): void
	{
		Assert::isNotA($a, stdClass::class);
		assertType('DateTimeImmutable', $a);

		Assert::isNotA($b, stdClass::class);
		assertType('class-string<DateTimeImmutable>', $b);

		Assert::nullOrIsNotA($c, stdClass::class);
		assertType('DateTimeImmutable|null', $c);

		Assert::isNotA(Foo::class, stdClass::class);
		assertType('\'PHPStan\\\Type\\\WebMozartAssert\\\Foo\'', Foo::class);

		Assert::isNotA(Bar::class, stdClass::class);
		assertType('*NEVER*', Bar::class);
	}

	public function isArrayAccessible($a, $b): void
	{
		Assert::isArrayAccessible($a);
		assertType('array|ArrayAccess', $a);

		Assert::nullOrIsArrayAccessible($b);
		assertType('array|ArrayAccess|null', $b);
	}
}

class Foo {}

class Bar extends stdClass {}
