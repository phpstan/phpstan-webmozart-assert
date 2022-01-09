<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;

class TestStrings
{

	/**
	 * @param non-empty-string $b
	 */
	public function contains(string $a, string $b): void
	{
		Assert::contains($a, $a);
		\PHPStan\Testing\assertType('string', $a);

		Assert::contains($a, $b);
		\PHPStan\Testing\assertType('non-empty-string', $a);
	}

	/**
	 * @param non-empty-string $b
	 */
	public function startsWith(string $a, string $b): void
	{
		Assert::startsWith($a, $a);
		\PHPStan\Testing\assertType('string', $a);

		Assert::startsWith($a, $b);
		\PHPStan\Testing\assertType('non-empty-string', $a);
	}

	public function startsWithLetter(string $a): void
	{
		Assert::startsWithLetter($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);
	}

	/**
	 * @param non-empty-string $b
	 */
	public function endsWith(string $a, string $b): void
	{
		Assert::endsWith($a, $a);
		\PHPStan\Testing\assertType('string', $a);

		Assert::endsWith($a, $b);
		\PHPStan\Testing\assertType('non-empty-string', $a);
	}

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

	public function unicodeLetters($a): void
	{
		Assert::unicodeLetters($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);
	}

	public function alpha($a): void
	{
		Assert::alpha($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);
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

	public function uuid(string $a): void
	{
		Assert::uuid($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);
	}

	public function ip($a): void
	{
		Assert::ip($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);
	}

	public function ipv4($a): void
	{
		Assert::ipv4($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);
	}

	public function ipv6($a): void
	{
		Assert::ipv6($a);
		\PHPStan\Testing\assertType('non-empty-string', $a);
	}

	public function email($a): void
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
