<?php declare(strict_types=1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;

class ComparisonTest
{
	public function true($a, $b): void
	{
		Assert::true($a);
		\PHPStan\Testing\assertType('true', $a);

		Assert::nullOrTrue($b);
		\PHPStan\Testing\assertType('true|null', $b);
	}

	public function false($a, $b): void
	{
		Assert::false($a);
		\PHPStan\Testing\assertType('false', $a);

		Assert::nullOrFalse($b);
		\PHPStan\Testing\assertType('false|null', $b);
	}

	public function notFalse($a, $b): void
	{
		/** @var int|false $a */
		Assert::notFalse($a);
		\PHPStan\Testing\assertType('int', $a);
	}

	public function null($a): void
	{
		Assert::null($a);
		\PHPStan\Testing\assertType('null', $a);
	}

	public function notNull(?int $a): void
	{
		Assert::notNull($a);
		\PHPStan\Testing\assertType('int', $a);
	}

	public function same($a, $b): void
	{
		Assert::same($a, 1);
		\PHPStan\Testing\assertType('1', $a);

		Assert::nullOrSame($b, 1);
		\PHPStan\Testing\assertType('1|null', $b);
	}

	/**
	 * @param -1|1 $a
	 */
	public function notSame($a): void
	{
		Assert::notSame($a, 1);
		\PHPStan\Testing\assertType('-1', $a);
	}

	public function inArray($a, $b): void
	{
		Assert::inArray($a, ['foo', 'bar']);
		\PHPStan\Testing\assertType('\'bar\'|\'foo\'', $a);

		Assert::nullOrInArray($b, ['foo', 'bar']);
		\PHPStan\Testing\assertType('\'bar\'|\'foo\'|null', $b);
	}

	public function oneOf($a, $b): void
	{
		Assert::oneOf($a, [1, 2]);
		\PHPStan\Testing\assertType('1|2', $a);

		Assert::nullOrOneOf($b, [1, 2]);
		\PHPStan\Testing\assertType('1|2|null', $b);
	}
}
