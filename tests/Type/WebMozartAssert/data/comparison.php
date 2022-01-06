<?php declare(strict_types=1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;

class ComparisonTest
{
	public function true($a): void
	{
		Assert::true($a);
		\PHPStan\Testing\assertType('true', $a);
	}

	public function false($a): void
	{
		Assert::false($a);
		\PHPStan\Testing\assertType('false', $a);
	}

	public function notFalse(int $a): void
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

	public function same($a): void
	{
		Assert::same($a, 1);
		\PHPStan\Testing\assertType('1', $a);
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

	public function oneOf($a): void
	{
		Assert::oneOf($a, [1, 2]);
		\PHPStan\Testing\assertType('1|2', $a);
	}
}
