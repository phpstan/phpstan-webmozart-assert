<?php

declare(strict_types=1);

namespace WebmozartAssertBug32;

use Webmozart\Assert\Assert;

/**
 * @param numeric-string $numericString
 */
function test(float $float, int $int, string $numericString): void
{
	Assert::integerish($float);
	Assert::integerish($int);
	Assert::integerish($numericString);
}
