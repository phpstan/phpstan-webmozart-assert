<?php

declare(strict_types=1);

namespace WebmozartAssertBug8;

use Webmozart\Assert\Assert;

class Bug8
{

	public function foo(string $a): void
	{
		Assert::numeric($a);
		Assert::numeric($a);
		Assert::numeric('foo');
		Assert::numeric('17.19');
	}

}
