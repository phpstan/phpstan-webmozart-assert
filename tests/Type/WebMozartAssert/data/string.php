<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;
use function PHPStan\Testing\assertType;

class TestStrings
{

	/**
	 * @param non-empty-string $b
	 */
	public function contains(string $a, string $b): void
	{
		Assert::contains($a, $a);
		assertType('string', $a);

		Assert::contains($a, $b);
		assertType('non-empty-string', $a);
	}

	/**
	 * @param non-empty-string $b
	 */
	public function startsWith(string $a, string $b): void
	{
		Assert::startsWith($a, $a);
		assertType('string', $a);

		Assert::startsWith($a, $b);
		assertType('non-empty-string', $a);
	}

	public function startsWithLetter(string $a): void
	{
		Assert::startsWithLetter($a);
		assertType('non-empty-string', $a);
	}

	/**
	 * @param non-empty-string $b
	 */
	public function endsWith(string $a, string $b): void
	{
		Assert::endsWith($a, $a);
		assertType('string', $a);

		Assert::endsWith($a, $b);
		assertType('non-empty-string', $a);
	}

	public function unicodeLetters($a): void
	{
		Assert::unicodeLetters($a);
		assertType('non-empty-string', $a);
	}

	public function alpha($a): void
	{
		Assert::alpha($a);
		assertType('non-empty-string', $a);
	}

	public function digits(string $a): void
	{
		Assert::digits($a);
		assertType('non-empty-string', $a);
	}

	public function alnum(string $a): void
	{
		Assert::alnum($a);
		assertType('non-empty-string', $a);
	}

	public function lower(string $a): void
	{
		Assert::lower($a);
		assertType('non-empty-string', $a);
	}

	public function upper(string $a): void
	{
		Assert::upper($a);
		assertType('non-empty-string', $a);
	}

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

	public function uuid(string $a): void
	{
		Assert::uuid($a);
		assertType('non-empty-string', $a);
	}

	public function ip($a): void
	{
		Assert::ip($a);
		assertType('non-empty-string', $a);
	}

	public function ipv4($a): void
	{
		Assert::ipv4($a);
		assertType('non-empty-string', $a);
	}

	public function ipv6($a): void
	{
		Assert::ipv6($a);
		assertType('non-empty-string', $a);
	}

	public function email($a): void
	{
		Assert::email($a);
		assertType('non-empty-string', $a);
	}

	public function notWhitespaceOnly(string $a): void
	{
		Assert::notWhitespaceOnly($a);
		assertType('non-empty-string', $a);
	}

}
