<?php declare(strict_types = 1);

namespace WebmozartAssertImpossibleCheckEqNotEq;

use DateTimeInterface;
use stdClass;
use Webmozart\Assert\Assert;

class ImpossibleCheckEqNotEq
{

	public function sameInstancesAreAlwaysEqual(stdClass $a, stdClass $b): void
	{
		Assert::eq($a, $a); // will always evaluate to true
		Assert::notEq($b, $b); // will always evaluate to false
	}

	public function instancesOfTheSameTypeAreNotIdenticalButCouldBeEqual(stdClass $a, stdClass $b, stdClass $c, stdClass $d): void
	{
		Assert::eq($a, $b);
		Assert::notEq($c, $d);

		Assert::eq(self::createStdClass(), self::createStdClass());
		Assert::notEq(self::createStdClass(), self::createStdClass());
	}

	public function looseVariableComparisonsAreNotSupported(stdClass $a, DateTimeInterface $b, stdClass $c, DateTimeInterface $d, string $e, int $f, string $g, int $h): void
	{
		Assert::eq($a, $b);
		Assert::notEq($c, $d);
		Assert::eq($e, $f);
		Assert::notEq($g, $h);
	}

	public function constantComparisons(): void
	{
		Assert::eq(1, '1'); // will always evaluate to true
		Assert::notEq(1, '1'); // will always evaluate to false
		Assert::eq(1, true); // will always evaluate to true
		Assert::notEq(1, true); // will always evaluate to false
		Assert::eq('php', true); // will always evaluate to true
		Assert::notEq('php', true); // will always evaluate to false
		Assert::eq('', false); // will always evaluate to true
		Assert::notEq('', false); // will always evaluate to false

		Assert::eq(1, 1); // will always evaluate to true
		Assert::notEq(1, 1); // will always evaluate to false
		Assert::eq(true, true); // will always evaluate to true
		Assert::notEq(true, true); // will always evaluate to false
		Assert::eq('php', 'php'); // will always evaluate to true
		Assert::notEq('php', 'php'); // will always evaluate to false
	}

	public function instancesOfDifferentTypesAreNeverEqual(stdClass $a, stdClass $b, stdClass $c, stdClass $d, stdClass $e, stdClass $f): void
	{
		Assert::eq($a, new stdClass());
		Assert::eq($b, self::createStdClass());
		Assert::notEq($c, new stdClass());
		Assert::notEq($d, self::createStdClass());
	}

	public static function createStdClass(): stdClass
	{
		return new stdClass();
	}

}
