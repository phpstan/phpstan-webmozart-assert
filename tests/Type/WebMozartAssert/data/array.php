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
	 * @param mixed $a
	 */
	public function validArrayKey($a, bool $b): void
	{
		Assert::validArrayKey($a);
		\PHPStan\Testing\assertType('int|string', $a);

		Assert::validArrayKey($b);
		\PHPStan\Testing\assertType('*NEVER*', $b);
	}

}
