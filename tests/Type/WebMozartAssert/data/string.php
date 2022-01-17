<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;

class TestStrings
{

	public function length(string $a, string $b): void
	{
		Assert::length($a, 0);
		\PHPStan\Testing\assertType('\'\'', $a);

		Assert::length($b, 1);
		\PHPStan\Testing\assertType('non-empty-string', $b);
	}

	public function minLength(string $a, string $b): void
	{
		Assert::minLength($a, 0);
		\PHPStan\Testing\assertType('string', $a);

		Assert::minLength($b, 1);
		\PHPStan\Testing\assertType('non-empty-string', $b);
	}

	public function maxLength(string $a, string $b): void
	{
		Assert::maxLength($a, 0);
		\PHPStan\Testing\assertType('\'\'', $a);

		Assert::maxLength($b, 1);
		\PHPStan\Testing\assertType('string', $b);
	}

	public function lengthBetween(string $a, string $b, string $c, string $d): void
	{
		Assert::lengthBetween($a, 0, 0);
		\PHPStan\Testing\assertType('\'\'', $a);

		Assert::lengthBetween($b, 0, 1);
		\PHPStan\Testing\assertType('string', $b);

		Assert::lengthBetween($c, 1, 0);
		\PHPStan\Testing\assertType('*NEVER*', $c);

		Assert::lengthBetween($d, 1, 1);
		\PHPStan\Testing\assertType('non-empty-string', $d);
	}

}
