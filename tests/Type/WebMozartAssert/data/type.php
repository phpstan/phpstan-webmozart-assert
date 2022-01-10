<?php declare(strict_types=1);

namespace PHPStan\Type\WebMozartAssert;

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

	public function integerish($a, $b): void
	{
		Assert::integerish($a);
		\PHPStan\Testing\assertType('float|int|numeric-string', $a);

		Assert::nullOrIntegerish($b);
		\PHPStan\Testing\assertType('float|int|numeric-string|null', $b);
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

	public function isInstanceOf($a, $b): void
	{
		Assert::isInstanceOf($a, self::class);
		\PHPStan\Testing\assertType('PHPStan\Type\WebMozartAssert\TypeTest', $a);

		Assert::nullOrIsInstanceOf($b, self::class);
		\PHPStan\Testing\assertType('PHPStan\Type\WebMozartAssert\TypeTest|null', $b);
	}

	/**
	 * @param Foo|Bar $a
	 */
	public function notInstanceOf($a): void
	{
		Assert::notInstanceOf($a, Bar::class);
		\PHPStan\Testing\assertType('PHPStan\Type\WebMozartAssert\Foo', $a);
	}

	public function isArrayAccessible($a, $b): void
	{
		Assert::isArrayAccessible($a);
		\PHPStan\Testing\assertType('array|ArrayAccess', $a);

		Assert::nullOrIsArrayAccessible($b);
		\PHPStan\Testing\assertType('array|ArrayAccess|null', $b);
	}
}
