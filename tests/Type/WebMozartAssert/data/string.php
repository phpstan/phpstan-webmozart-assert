<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;

class TestStrings
{

	public function length(string $a, string $b, string $c): void
	{
		Assert::length($a, 0);
		\PHPStan\Testing\assertType('\'\'', $a);

		Assert::length($b, 1);
		\PHPStan\Testing\assertType('non-empty-string', $b);

		Assert::nullOrLength($c, 1);
		\PHPStan\Testing\assertType('non-empty-string', $c); // should be non-empty-string|null
	}

	public function minLength(string $a, string $b, string $c): void
	{
		Assert::minLength($a, 0);
		\PHPStan\Testing\assertType('string', $a);

		Assert::minLength($b, 1);
		\PHPStan\Testing\assertType('non-empty-string', $b);

		Assert::nullOrMinLength($c, 1);
		\PHPStan\Testing\assertType('non-empty-string', $c); // should be non-empty-string|null
	}

	public function maxLength(string $a, string $b, string $c): void
	{
		Assert::maxLength($a, 0);
		\PHPStan\Testing\assertType('\'\'', $a);

		Assert::maxLength($b, 1);
		\PHPStan\Testing\assertType('string', $b);

		Assert::nullOrMaxLength($c, 1);
		\PHPStan\Testing\assertType('string', $c);  // should be string|null
	}

	public function lengthBetween(string $a, string $b, string $c, string $d, string $e): void
	{
		Assert::lengthBetween($a, 0, 0);
		\PHPStan\Testing\assertType('\'\'', $a);

		Assert::lengthBetween($b, 0, 1);
		\PHPStan\Testing\assertType('string', $b);

		Assert::lengthBetween($c, 1, 0);
		\PHPStan\Testing\assertType('*NEVER*', $c);

		Assert::lengthBetween($d, 1, 1);
		\PHPStan\Testing\assertType('non-empty-string', $d);

		Assert::nullOrLengthBetween($e, 1, 1);
		\PHPStan\Testing\assertType('non-empty-string', $e); // should be non-empty-string|null
	}

}
