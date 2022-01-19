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
	 * @param array{foo?: string, bar?: string} $a
	 */
	public function keyExists(array $a): void
	{
		Assert::keyExists($a, 'foo');
		\PHPStan\Testing\assertType('array{foo: string, bar?: string}', $a);
	}

	public function validArrayKey($a, bool $b, $c): void
	{
		Assert::validArrayKey($a);
		\PHPStan\Testing\assertType('int|string', $a);

		Assert::validArrayKey($b);
		\PHPStan\Testing\assertType('*NEVER*', $b);

		Assert::nullOrValidArrayKey($c);
		\PHPStan\Testing\assertType('int|string|null', $c);
	}

	/**
	 * @param int[] $a
	 * @param int[] $b
	 */
	public function count(array $a, array $b): void
	{
		Assert::count($a, 1);
		\PHPStan\Testing\assertType('non-empty-array<int>', $a);

		Assert::count($b, 0);
		\PHPStan\Testing\assertType('array{}', $b);
	}

	/**
	 * @param int[] $a
	 * @param int[] $b
	 */
	public function minCount(array $a, array $b): void
	{
		Assert::minCount($a, 1);
		\PHPStan\Testing\assertType('non-empty-array<int>', $a);

		Assert::minCount($b, 0);
		\PHPStan\Testing\assertType('array<int>', $b);
	}

	/**
	 * @param int[] $a
	 * @param int[] $b
	 */
	public function maxCount(array $a, array $b): void
	{
		Assert::maxCount($a, 1);
		\PHPStan\Testing\assertType('array<int>', $a);

		Assert::maxCount($b, 0);
		\PHPStan\Testing\assertType('array{}', $b);
	}

	/**
	 * @param int[] $a
	 * @param int[] $b
	 * @param int[] $c
	 * @param int[] $d
	 */
	public function countBetween(array $a, array $b, array $c, array $d): void
	{
		Assert::countBetween($a, 1, 2);
		\PHPStan\Testing\assertType('non-empty-array<int>', $a);

		Assert::countBetween($b, 0, 2);
		\PHPStan\Testing\assertType('array<int>', $b);

		Assert::countBetween($c, 0, 0);
		\PHPStan\Testing\assertType('array{}', $c);

		Assert::countBetween($d, 2, 0);
		\PHPStan\Testing\assertType('*NEVER*', $d);
	}

	public function isList($a, $b): void
	{
		Assert::isList($a);
		\PHPStan\Testing\assertType('array<int, mixed>', $a);

		Assert::nullOrIsList($b);
		\PHPStan\Testing\assertType('array<int, mixed>|null', $b);
	}

}
