<?php declare(strict_types = 1);

namespace WebmozartAssertBug118;

use DateTime;
use Webmozart\Assert\Assert;

function test(float $a, DateTime $b): void
{
	Assert::range($a, 0, 1);
	Assert::range($b, 123456789, 9876543321);
}
