<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;

class TestStrings
{

	public function length(string $a, string $b, string $c, ?string $d): void
	{
		Assert::length($a, 0);
		\PHPStan\Testing\assertType('\'\'', $a);

		Assert::length($b, 1);
		\PHPStan\Testing\assertType('non-empty-string', $b);

		Assert::nullOrLength($c, 1);
		\PHPStan\Testing\assertType('non-empty-string', $c);

		Assert::nullOrLength($d, 1);
		\PHPStan\Testing\assertType('non-empty-string|null', $d);
	}

	public function minLength(string $a, string $b, string $c, ?string $d): void
	{
		Assert::minLength($a, 0);
		\PHPStan\Testing\assertType('string', $a);

		Assert::minLength($b, 1);
		\PHPStan\Testing\assertType('non-empty-string', $b);

		Assert::nullOrMinLength($c, 1);
		\PHPStan\Testing\assertType('non-empty-string', $c);

		Assert::nullOrMinLength($d, 1);
		\PHPStan\Testing\assertType('non-empty-string|null', $d);
	}

	public function maxLength(string $a, string $b, string $c, ?string $d): void
	{
		Assert::maxLength($a, 0);
		\PHPStan\Testing\assertType('\'\'', $a);

		Assert::maxLength($b, 1);
		\PHPStan\Testing\assertType('string', $b);

		Assert::nullOrMaxLength($c, 1);
		\PHPStan\Testing\assertType('string', $c);

		Assert::nullOrMaxLength($d, 1);
		\PHPStan\Testing\assertType('string|null', $d);
	}

	public function lengthBetween(string $a, string $b, string $c, string $d, string $e, ?string $f): void
	{
		Assert::lengthBetween($a, 0, 0);
		\PHPStan\Testing\assertType('\'\'', $a);

		Assert::lengthBetween($b, 0, 1);
		\PHPStan\Testing\assertType('string', $b);

		Assert::lengthBetween($c, 1, 0);
		\PHPStan\Testing\assertType('non-empty-string', $c); // this looks like a bug or undefined behaviour

		Assert::lengthBetween($d, 1, 1);
		\PHPStan\Testing\assertType('non-empty-string', $d);

		Assert::nullOrLengthBetween($e, 1, 1);
		\PHPStan\Testing\assertType('non-empty-string', $e);

		Assert::nullOrLengthBetween($f, 1, 1);
		\PHPStan\Testing\assertType('non-empty-string|null', $f);
	}

	public function uuid(string $a): void
	{
		Assert::uuid($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);
	}

	public function ip(string $a): void
	{
		Assert::ip($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);
	}

	public function ipv4(string $a): void
	{
		Assert::ipv4($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);
	}

	public function ipv6(string $a): void
	{
		Assert::ipv6($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);
	}

	public function email(string $a): void
	{
		Assert::email($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);
	}

	public function notWhitespaceOnly(string $a): void
	{
		Assert::notWhitespaceOnly($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);
	}
}
