<?php declare(strict_types=1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;

class ArrayTest
{

	/**
	 * @param array{foo?: string, bar?: string} $a
	 */
	public function keyNotExists(array $a): void
	{
		Assert::keyNotExists($a, 'bar');
		\PHPStan\Testing\assertType('array{foo?: string}', $a);
	}

	/**
	 * @param array{foo?: string, bar?: string} $things
	 */
	public function keyExists(array $things): void
	{
		Assert::keyExists($things, 'foo');
		\PHPStan\Testing\assertType('array{foo: string, bar?: string}', $things);
	}

	/**
	 * @param mixed $a
	 */
	public function validArrayKey($a, bool $b): void
	{
		Assert::validArrayKey($a);
		\PHPStan\Testing\assertType('int|string', $a);

		Assert::validArrayKey($b);
		\PHPStan\Testing\assertType('*NEVER*', $b);
	}

	/**
	 * @param int[] $a
	 */
	public function count(array $a): void
	{
		Assert::count($a, 1);
		$b = array_pop($a);
		$c = array_pop($a);
		\PHPStan\Testing\assertType('int', $b);
		\PHPStan\Testing\assertType('int|null', $c);
	}

	/**
	 * @param int[] $a
	 */
	public function minCount(array $a): void
	{
		Assert::minCount($a, 1);
		$b = array_pop($a);
		\PHPStan\Testing\assertType('int', $b);
	}

	/**
	 * @param int[] $a
	 */
	public function maxCount(array $a): void
	{
		Assert::maxCount($a, 1);
		\PHPStan\Testing\assertType('array<int>', $a);
		Assert::maxCount($a, 0);
		\PHPStan\Testing\assertType('array{}', $a);
	}

	/**
	 * @param int[] $a
	 */
	public function countBetween(array $a): void
	{
		Assert::countBetween($a, 1, 2);
		$b = array_pop($a);
		$c = array_pop($a);
		$d = array_pop($a);
		\PHPStan\Testing\assertType('int', $b);
		\PHPStan\Testing\assertType('int|null', $c);
		\PHPStan\Testing\assertType('int|null', $d);
	}

	/**
	 * @param mixed $a
	 */
	public function isList($a): void
	{
		Assert::isList($a);
		\PHPStan\Testing\assertType('array', $a);
		Assert::allString($a);
		\PHPStan\Testing\assertType('array<string>', $a);
	}
}
