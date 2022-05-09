<?php declare(strict_types = 1);

namespace WebmozartAssertBug119;

use DateTime;
use Webmozart\Assert\Assert;

function test(float $a, float $b, float $c, float $d, DateTime $e): void
{
	Assert::greaterThan($a, 0);
	Assert::greaterThanEq($b, 0);
	Assert::lessThan($c, 0);
	Assert::lessThanEq($d, 0);
	Assert::greaterThanEq($e, 639828000);
}
