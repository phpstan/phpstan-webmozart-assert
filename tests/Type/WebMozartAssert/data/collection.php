<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use stdClass;
use Webmozart\Assert\Assert;

class CollectionTest
{

	public function allString(array $a): void
	{
		Assert::allString($a);
		\PHPStan\Testing\assertType('array<string>', $a);
	}

	public function allStringNotEmpty(array $a): void
	{
		Assert::allStringNotEmpty($a);
		\PHPStan\Testing\assertType('array<string>', $a); // should be array<non-empty-string>
	}

	public function allInteger(array $a, iterable $b, iterable $c): void
	{
		Assert::allInteger($a);
		\PHPStan\Testing\assertType('array<int>', $a);

		Assert::allInteger($b);
		\PHPStan\Testing\assertType('iterable<int>', $b);
	}

	public function allInstanceOf(array $a, array $b, array $c): void
	{
		Assert::allIsInstanceOf($a, stdClass::class);
		\PHPStan\Testing\assertType('array<stdClass>', $a);

		Assert::allIsInstanceOf($b, new stdClass());
		\PHPStan\Testing\assertType('array<stdClass>', $b);

		Assert::allIsInstanceOf($c, 17);
		\PHPStan\Testing\assertType('array', $c);
	}

	/**
	 * @param (CollectionFoo|CollectionBar)[] $a
	 * @param (CollectionFoo|stdClass)[] $b
	 * @param CollectionFoo[] $c
	 */
	public function allNotInstanceOf(array $a, array $b, array $c): void
	{
		Assert::allNotInstanceOf($a, CollectionBar::class);
		\PHPStan\Testing\assertType('array<PHPStan\Type\WebMozartAssert\CollectionFoo>', $a);

		Assert::allNotInstanceOf($b, new stdClass());
		\PHPStan\Testing\assertType('array<PHPStan\Type\WebMozartAssert\CollectionFoo>', $b);

		Assert::allNotInstanceOf($c, 17);
		\PHPStan\Testing\assertType('array<PHPStan\Type\WebMozartAssert\CollectionFoo>', $c);
	}

	/**
	 * @param (int|null)[] $a
	 */
	public function allNotNull(array $a): void
	{
		Assert::allNotNull($a);
		\PHPStan\Testing\assertType('array<int>', $a);
	}

	/**
	 * @param array{-1|1, -2|2, -3|3} $a
	 */
	public function allNotSame(array $a): void
	{
		Assert::allNotSame($a, -1);
		\PHPStan\Testing\assertType('array{1, -2|2, -3|3}', $a);
	}

	public function allSubclassOf(array $a, $b): void
	{
		Assert::allSubclassOf($a, self::class);
		\PHPStan\Testing\assertType('array<class-string<PHPStan\Type\WebMozartAssert\CollectionTest>|PHPStan\Type\WebMozartAssert\CollectionTest>', $a);

		Assert::allSubclassOf($b, self::class);
		\PHPStan\Testing\assertType('iterable<class-string<PHPStan\Type\WebMozartAssert\CollectionTest>|PHPStan\Type\WebMozartAssert\CollectionTest>', $b);
	}

	/**
	 * @param array<array{id?: int}> $a
	 * @param array<int, array<string, mixed>> $b
	 *
	 */
	public function allKeyExists(array $a, array $b, array $c): void
	{
		Assert::allKeyExists($a, 'id');
		\PHPStan\Testing\assertType('array<array{id: int}>', $a);

		Assert::allKeyExists($b, 'id');
		\PHPStan\Testing\assertType('array<int, array<string, mixed>&hasOffset(\'id\')>', $b);

		Assert::allKeyExists($c, 'id');
		\PHPStan\Testing\assertType('array<array&hasOffset(\'id\')>', $c);
	}

}

class CollectionFoo
{

}

interface CollectionBar
{

}
