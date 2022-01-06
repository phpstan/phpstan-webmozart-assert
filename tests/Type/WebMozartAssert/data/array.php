<?php declare(strict_types=1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;

class ArrayTest
{

	/**
	 * @param array{foo?: string, bar?: string} $a
	 */
	public function keyExists(array $a): void
	{
		Assert::keyExists($a, 'foo');
		\PHPStan\Testing\assertType('array{foo: string, bar?: string}', $a);
	}

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
		$a1 = array_pop($a);
		$a2 = array_pop($a);
		\PHPStan\Testing\assertType('int', $a1);
		\PHPStan\Testing\assertType('int|null', $a2);
	}

	/**
	 * @param int[] $a
	 */
	public function minCount(array $a): void
	{
		Assert::minCount($a, 1);
		$a1 = array_pop($a);
		\PHPStan\Testing\assertType('int', $a1);
	}

	public function isList($a): void
	{
		Assert::isList($a);
		\PHPStan\Testing\assertType('array', $a);
	}

}
