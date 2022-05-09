<?php declare(strict_types = 1);

namespace WebmozartAssertBug33;

use Webmozart\Assert\Assert;

class Bug33
{

	public function foo(?string $bar)
	{
		Assert::nullOrStringNotEmpty($bar);
	}

}
