<?php

declare(strict_types=1);

use Webmozart\Assert\Assert;

class Foo
{
	/**
	 * @param mixed $a
	 */
	public function validArrayKey($a, bool $b): void
	{
		Assert::validArrayKey($a);
		\PHPStan\Testing\assertType('int|string', $a);

		Assert::validArrayKey($b);
		\PHPStan\Testing\assertType('*NEVER*', $b);
	}
}
