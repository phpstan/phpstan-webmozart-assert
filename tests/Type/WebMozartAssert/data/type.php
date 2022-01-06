<?php declare(strict_types=1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;

class TypeTest
{
	/**
	 * @param mixed $a
	 */
	public function positiveInteger($a): void
	{
		Assert::positiveInteger($a);
		\PHPStan\Testing\assertType('int<1, max>', $a);

		$b = -1;
		Assert::positiveInteger($b);
		\PHPStan\Testing\assertType('*NEVER*', $b);
	}

	public function natural($a): void
	{
		Assert::natural($a);
		\PHPStan\Testing\assertType('int<0, max>', $a);

		$b = -1;
		Assert::natural($b);
		\PHPStan\Testing\assertType('*NEVER*', $b);
	}
}
