<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;

class TestStrings
{

	/**
	 * @param non-empty-string $b
	 */
	public function contains(string $a, string $b, string $c, $d): void
	{
		Assert::contains($a, $a);
		\PHPStan\Testing\assertType('string', $a);

		Assert::contains($a, $b);
		\PHPStan\Testing\assertType('non-empty-string', $a);

		Assert::nullOrContains($c, $b);
		\PHPStan\Testing\assertType('non-empty-string|null', $c);

		Assert::contains($d, $b);
		\PHPStan\Testing\assertType('mixed', $d); // not further narrowed down because the assertion expects a string
	}

	/**
	 * @param non-empty-string $b
	 */
	public function startsWith(string $a, string $b, string $c): void
	{
		Assert::startsWith($a, $a);
		\PHPStan\Testing\assertType('string', $a);

		Assert::startsWith($a, $b);
		\PHPStan\Testing\assertType('non-empty-string', $a);

		Assert::nullOrStartsWith($c, $b);
		\PHPStan\Testing\assertType('non-empty-string|null', $c);
	}

	public function startsWithLetter(string $a, string $b): void
	{
		Assert::startsWithLetter($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);

		Assert::nullOrStartsWithLetter($b);
		\PHPStan\Testing\assertType('non-empty-string|null', $b);
	}

	/**
	 * @param non-empty-string $b
	 */
	public function endsWith(string $a, string $b, string $c): void
	{
		Assert::endsWith($a, $a);
		\PHPStan\Testing\assertType('string', $a);

		Assert::endsWith($a, $b);
		\PHPStan\Testing\assertType('non-empty-string', $a);

		Assert::nullOrEndsWith($c, $b);
		\PHPStan\Testing\assertType('non-empty-string|null', $c);
	}

	public function length(string $a, string $b, string $c): void
	{
		Assert::length($a, 0);
		\PHPStan\Testing\assertType('\'\'', $a);

		Assert::length($b, 1);
		\PHPStan\Testing\assertType('non-empty-string', $b);

		Assert::nullOrLength($c, 1);
		\PHPStan\Testing\assertType('non-empty-string|null', $c);
	}

	public function minLength(string $a, string $b, string $c): void
	{
		Assert::minLength($a, 0);
		\PHPStan\Testing\assertType('string', $a);

		Assert::minLength($b, 1);
		\PHPStan\Testing\assertType('non-empty-string', $b);

		Assert::nullOrMinLength($c, 1);
		\PHPStan\Testing\assertType('non-empty-string|null', $c);
	}

	public function maxLength(string $a, string $b, string $c): void
	{
		Assert::maxLength($a, 0);
		\PHPStan\Testing\assertType('\'\'', $a);

		Assert::maxLength($b, 1);
		\PHPStan\Testing\assertType('string', $b);

		Assert::nullOrMaxLength($c, 1);
		\PHPStan\Testing\assertType('string|null', $c);
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
		\PHPStan\Testing\assertType('non-empty-string|null', $e);
	}

	public function unicodeLetters($a, $b): void
	{
		Assert::unicodeLetters($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);

		Assert::nullOrUnicodeLetters($b);
		\PHPStan\Testing\assertType('non-empty-string|null', $b);
	}

	public function alpha($a, $b): void
	{
		Assert::alpha($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);

		Assert::nullOrAlpha($b);
		\PHPStan\Testing\assertType('non-empty-string|null', $b);
	}

	public function digits(string $a): void
	{
		Assert::digits($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);
	}

	public function alnum(string $a): void
	{
		Assert::alnum($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);
	}

	public function lower(string $a): void
	{
		Assert::lower($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);
	}

	public function upper(string $a): void
	{
		Assert::upper($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);
	}

	public function uuid(string $a, string $b): void
	{
		Assert::uuid($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);

		Assert::nullOrUuid($b);
		\PHPStan\Testing\assertType('non-empty-string|null', $b);
	}

	public function ip($a, $b): void
	{
		Assert::ip($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);

		Assert::nullOrIp($b);
		\PHPStan\Testing\assertType('non-empty-string|null', $b);
	}

	public function ipv4($a, $b): void
	{
		Assert::ipv4($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);

		Assert::nullOrIpv4($b);
		\PHPStan\Testing\assertType('non-empty-string|null', $b);
	}

	public function ipv6($a, $b): void
	{
		Assert::ipv6($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);

		Assert::nullOrIpv6($b);
		\PHPStan\Testing\assertType('non-empty-string|null', $b);
	}

	public function email($a, $b): void
	{
		Assert::email($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);

		Assert::nullOrEmail($b);
		\PHPStan\Testing\assertType('non-empty-string|null', $b);
	}

	public function notWhitespaceOnly(string $a, string $b, $c): void
	{
		Assert::notWhitespaceOnly($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);

		Assert::nullOrNotWhitespaceOnly($b);
		\PHPStan\Testing\assertType('non-empty-string|null', $b);

		Assert::notWhitespaceOnly($c);
		\PHPStan\Testing\assertType('mixed', $c); // not further narrowed down because the assertion expects a string
	}

}
