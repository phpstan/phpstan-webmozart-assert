<?php declare(strict_types=1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;
use function PHPStan\Testing\assertType;

class ArrayTest
{

	/**
	 * @param array{foo?: string, bar?: string} $a
	 */
	public function keyNotExists(array $a): void
	{
		Assert::keyNotExists($a, 'bar');
		assertType('array{foo?: string}', $a);
	}

	/**
	 * @param array{foo?: string, bar?: string} $a
	 */
	public function keyExists(array $a): void
	{
		Assert::keyExists($a, 'foo');
		assertType('array{foo: string, bar?: string}', $a);
	}

	public function validArrayKey($a, bool $b, $c): void
	{
		Assert::validArrayKey($a);
		assertType('int|string', $a);

		Assert::validArrayKey($b);
		assertType('*NEVER*', $b);

		Assert::nullOrValidArrayKey($c);
		assertType('int|string|null', $c);
	}

	/**
	 * @param int[] $a
	 * @param int[] $b
	 */
	public function count(array $a, array $b): void
	{
		Assert::count($a, 1);
		assertType('non-empty-array<int>', $a);

		Assert::count($b, 0);
		assertType('array{}', $b);
	}

	/**
	 * @param int[] $a
	 * @param int[] $b
	 */
	public function minCount(array $a, array $b): void
	{
		Assert::minCount($a, 1);
		assertType('non-empty-array<int>', $a);

		Assert::minCount($b, 0);
		assertType('array<int>', $b);
	}

	/**
	 * @param int[] $a
	 * @param int[] $b
	 */
	public function maxCount(array $a, array $b): void
	{
		Assert::maxCount($a, 1);
		assertType('array<int>', $a);

		Assert::maxCount($b, 0);
		assertType('array{}', $b);
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
		assertType('non-empty-array<int>', $a);

		Assert::countBetween($b, 0, 2);
		assertType('array<int>', $b);

		Assert::countBetween($c, 0, 0);
		assertType('array{}', $c);

		Assert::countBetween($d, 2, 0);
		assertType('*NEVER*', $d);
	}

	public function isList($a, $b): void
	{
		Assert::isList($a);
		assertType('list<mixed>', $a);

		Assert::nullOrIsList($b);
		assertType('list<mixed>|null', $b);
	}

	public function isNonEmptyList($a, $b): void
	{
		Assert::isNonEmptyList($a);
		assertType('non-empty-list<mixed>', $a);

		Assert::nullOrIsNonEmptyList($b);
		assertType('non-empty-list<mixed>|null', $b);
	}

	public function isMap($a, $b): void
	{
		Assert::isMap($a);
		assertType('array<string, mixed>', $a);

		Assert::nullOrIsMap($b);
		assertType('array<string, mixed>|null', $b);
	}

	public function isNonEmptyMap($a, $b): void
	{
		Assert::isNonEmptyMap($a);
		assertType('non-empty-array<string, mixed>', $a);

		Assert::nullOrIsNonEmptyMap($b);
		assertType('non-empty-array<string, mixed>|null', $b);
	}

}
