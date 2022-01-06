<?php declare(strict_types=1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;

class TypeTest
{
	public function string($a): void
	{
		Assert::string($a);
		\PHPStan\Testing\assertType('string', $a);
	}

	public function stringNotEmpty($a): void
	{
		Assert::stringNotEmpty($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);
	}

	public function integer($a): void
	{
		Assert::integer($a);
		\PHPStan\Testing\assertType('int', $a);
	}

	public function integerish($a): void
	{
		Assert::integerish($a);
		\PHPStan\Testing\assertType('float|int|numeric-string', $a);
	}

	public function positiveInteger($a): void
	{
		Assert::positiveInteger($a);
		\PHPStan\Testing\assertType('int<1, max>', $a);

		$b = -1;
		Assert::positiveInteger($b);
		\PHPStan\Testing\assertType('*NEVER*', $b);
	}

	public function float($a): void
	{
		Assert::float($a);
		\PHPStan\Testing\assertType('float', $a);
	}

	public function numeric($a): void
	{
		Assert::numeric($a);
		\PHPStan\Testing\assertType('float|int|numeric-string', $a);
	}

	public function natural($a): void
	{
		Assert::natural($a);
		\PHPStan\Testing\assertType('int<0, max>', $a);

		$b = -1;
		Assert::natural($b);
		\PHPStan\Testing\assertType('*NEVER*', $b);
	}

	public function boolean($a): void
	{
		Assert::boolean($a);
		\PHPStan\Testing\assertType('bool', $a);
	}

	public function scalar($a): void
	{
		Assert::scalar($a);
		\PHPStan\Testing\assertType('bool|float|int|string', $a);
	}

	public function object($a): void
	{
		Assert::object($a);
		\PHPStan\Testing\assertType('object', $a);
	}

	public function resource($a): void
	{
		Assert::resource($a);
		\PHPStan\Testing\assertType('resource', $a);
	}

	public function isCallable($a): void
	{
		Assert::isCallable($a);
		\PHPStan\Testing\assertType('callable(): mixed', $a);
	}

	public function isArray($a): void
	{
		Assert::isArray($a);
		\PHPStan\Testing\assertType('array', $a);
	}

	public function isIterable($a): void
	{
		Assert::isIterable($a);
		\PHPStan\Testing\assertType('array|Traversable', $a);
	}

	public function isCountable($a): void
	{
		Assert::isCountable($a);
		\PHPStan\Testing\assertType('array|Countable', $a);
	}

	public function isInstanceOf($a): void
	{
		Assert::isInstanceOf($a, self::class);
		\PHPStan\Testing\assertType('PHPStan\Type\WebMozartAssert\TypeTest', $a);
	}

	/**
	 * @param Foo|Bar $a
	 */
	public function notInstanceOf($a): void
	{
		Assert::notInstanceOf($a, Bar::class);
		\PHPStan\Testing\assertType('PHPStan\Type\WebMozartAssert\Foo', $a);
	}
}
