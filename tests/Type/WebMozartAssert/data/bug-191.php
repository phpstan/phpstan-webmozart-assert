<?php

use Webmozart\Assert\Assert;
use function PHPStan\Testing\assertType;

/**
 * @param array<int|string> $values
 * @param array<int> $allowed
 */
function bug191Narrow(array $values, array $allowed): void
{
	Assert::allInArray($values, $allowed);
	assertType('array<int>', $values);
}

/**
 * @param array<string> $enabledNames
 * @param array<string> $supportedNames
 */
function bug191SameTypes(array $enabledNames, array $supportedNames): void
{
	Assert::allInArray($enabledNames, $supportedNames);
	assertType('array<string>', $enabledNames);
}
