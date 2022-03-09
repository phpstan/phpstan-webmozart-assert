<?php declare(strict_types=1);

namespace WebmozartAssertImpossibleCheckEqNotEq;

use DateTimeInterface;
use stdClass;
use Webmozart\Assert\Assert;

function sameInstancesAreAlwaysEqual(stdClass $a, stdClass $b): void
{
	Assert::eq($a, $a); // will always evaluate to true
	Assert::notEq($b, $b); // will always evaluate to false
}

function instancesOfTheSameTypeAreNotIdenticalButCouldBeEqual(stdClass $a, stdClass $b, stdClass $c, stdClass $d): void
{
	Assert::eq($a, $b);
	Assert::notEq($c, $d);

	Assert::eq(createStdClass(), createStdClass());
	Assert::notEq(createStdClass(), createStdClass());
}

function instancesOfDifferentTypesAreNeverEqual(stdClass $a, DateTimeInterface $b, stdClass $c, DateTimeInterface $d, stdClass $e, stdClass $f, stdClass $g, stdClass $h): void
{
	Assert::eq($a, $b); // ignored, should evaluate to false?
	Assert::notEq($c, $d); // ignored, should evaluate to true?

	Assert::eq($e, new stdClass()); // will always evaluate to true, should evaluate to false?
	Assert::notEq($f, new stdClass()); // ignored, should evaluate to true?

	Assert::eq($g, null); // will always evaluate to false
	Assert::notEq($h, null); // will always evaluate to true
}

function createStdClass(): stdClass
{
	return new stdClass();
}
