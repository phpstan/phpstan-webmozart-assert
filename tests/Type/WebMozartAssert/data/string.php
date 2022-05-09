<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;
use function PHPStan\Testing\assertType;

class TestStrings
{

	public function length(string $a, string $b, string $c, ?string $d): void
	{
		Assert::length($a, 0);
		assertType('\'\'', $a);

		Assert::length($b, 1);
		assertType('non-empty-string', $b);

		Assert::nullOrLength($c, 1);
		assertType('non-empty-string', $c);

		Assert::nullOrLength($d, 1);
		assertType('non-empty-string|null', $d);
	}

	public function minLength(string $a, string $b, string $c, ?string $d): void
	{
		Assert::minLength($a, 0);
		assertType('string', $a);

		Assert::minLength($b, 1);
		assertType('non-empty-string', $b);

		Assert::nullOrMinLength($c, 1);
		assertType('non-empty-string', $c);

		Assert::nullOrMinLength($d, 1);
		assertType('non-empty-string|null', $d);
	}

	public function maxLength(string $a, string $b, string $c, ?string $d): void
	{
		Assert::maxLength($a, 0);
		assertType('\'\'', $a);

		Assert::maxLength($b, 1);
		assertType('string', $b);

		Assert::nullOrMaxLength($c, 1);
		assertType('string', $c);

		Assert::nullOrMaxLength($d, 1);
		assertType('string|null', $d);
	}

	public function lengthBetween(string $a, string $b, string $c, string $d, string $e, ?string $f): void
	{
		Assert::lengthBetween($a, 0, 0);
		assertType('\'\'', $a);

		Assert::lengthBetween($b, 0, 1);
		assertType('string', $b);

		Assert::lengthBetween($c, 1, 0);
		assertType('*NEVER*', $c);

		Assert::lengthBetween($d, 1, 1);
		assertType('non-empty-string', $d);

		Assert::nullOrLengthBetween($e, 1, 1);
		assertType('non-empty-string', $e);

		Assert::nullOrLengthBetween($f, 1, 1);
		assertType('non-empty-string|null', $f);
	}

}
