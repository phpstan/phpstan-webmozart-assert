<?php declare(strict_types=1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;
use function PHPStan\Testing\assertType;

class ComparisonTest
{
	public function true($a, $b): void
	{
		Assert::true($a);
		assertType('true', $a);

		Assert::nullOrTrue($b);
		assertType('true|null', $b);
	}

	public function false($a, $b): void
	{
		Assert::false($a);
		assertType('false', $a);

		Assert::nullOrFalse($b);
		assertType('false|null', $b);
	}

	public function notFalse($a, $b): void
	{
		/** @var int|false $a */
		Assert::notFalse($a);
		assertType('int', $a);
	}

	public function null($a): void
	{
		Assert::null($a);
		assertType('null', $a);
	}

	public function notNull(?int $a): void
	{
		Assert::notNull($a);
		assertType('int', $a);
	}

	/** @param string|null $c */
	public function isEmpty(string $a, $b, $c): void
	{
		Assert::isEmpty($a);
		assertType("''|'0'", $a);

		Assert::isEmpty($b);
		assertType("0|0.0|''|'0'|array{}|false|null", $b);

		Assert::isEmpty($c);
		assertType("''|'0'|null", $c);
	}

	/** @param string|null $c */
	public function notEmpty(string $a, $b, $c): void
	{
		Assert::notEmpty($a);
		assertType('non-falsy-string', $a);

		Assert::notEmpty($b);
		assertType("mixed~0|0.0|''|'0'|array{}|false|null", $b);

		Assert::notEmpty($c);
		assertType('non-falsy-string', $c);
	}

	/**
	 * @param non-empty-string  $b2
	 */
	public function eq(?bool $a, string $b1, string $b2, $c, ?bool $d): void
	{
		Assert::eq($a, null);
		assertType('false|null', $a);

		Assert::eq($b1, $b2);
		assertType('non-empty-string', $b1);

		Assert::eq($c, false);
		assertType('0|0.0|\'\'|\'0\'|array{}|false|null', $c);

		Assert::nullOrEq($d, true);
		assertType('true|null', $d);
	}

	public function notEq(?bool $a, string $b, $c, ?bool $d): void
	{
		Assert::notEq($a, null);
		assertType('true', $a);

		Assert::notEq($b, '');
		assertType('non-empty-string', $b);

		Assert::notEq($c, true);
		assertType('0|0.0|\'\'|\'0\'|array{}|false|null', $c);

		Assert::nullOrNotEq($d, true);
		assertType('false|null', $d);
	}

	public function same($a, $b): void
	{
		Assert::same($a, 1);
		assertType('1', $a);

		Assert::nullOrSame($b, 1);
		assertType('1|null', $b);
	}

	/**
	 * @param -1|1 $a
	 */
	public function notSame($a): void
	{
		Assert::notSame($a, 1);
		assertType('-1', $a);
	}

	public function greaterThan(int $a, ?int $b): void
	{
		Assert::greaterThan($a, 17);
		assertType('int<18, max>', $a);

		Assert::nullOrGreaterThan($b, 17);
		assertType('int<18, max>|null', $b);
	}

	public function greaterThanEq(int $a, ?int $b): void
	{
		Assert::greaterThanEq($a, 17);
		assertType('int<17, max>', $a);

		Assert::nullOrGreaterThanEq($b, 17);
		assertType('int<17, max>|null', $b);
	}

	public function lessThan(int $a, ?int $b): void
	{
		Assert::lessThan($a, 17);
		assertType('int<min, 16>', $a);

		Assert::nullOrLessThan($b, 17);
		assertType('int<min, 16>|null', $b);
	}

	public function lessThanEq(int $a, ?int $b): void
	{
		Assert::lessThanEq($a, 17);
		assertType('int<min, 17>', $a);

		Assert::nullOrLessThanEq($b, 17);
		assertType('int<min, 17>|null', $b);
	}

	public function range(int $a, int $b, int $c, ?int $d): void
	{
		Assert::range($a, 17, 19);
		assertType('int<17, 19>', $a);

		Assert::range($b, 19, 17);
		assertType('*NEVER*', $b);

		Assert::range($c, 17, 17);
		assertType('17', $c);

		Assert::nullOrRange($d, 17, 19);
		assertType('int<17, 19>|null', $d);
	}

	public function inArray($a, $b): void
	{
		Assert::inArray($a, ['foo', 'bar']);
		assertType('\'bar\'|\'foo\'', $a);

		Assert::nullOrInArray($b, ['foo', 'bar']);
		assertType('\'bar\'|\'foo\'|null', $b);
	}

	public function oneOf($a, $b): void
	{
		Assert::oneOf($a, [1, 2]);
		assertType('1|2', $a);

		Assert::nullOrOneOf($b, [1, 2]);
		assertType('1|2|null', $b);
	}
}
