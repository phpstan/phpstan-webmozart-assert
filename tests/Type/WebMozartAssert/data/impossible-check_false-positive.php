<?php

namespace WebmozartAssertImpossibleCheck;

use Webmozart\Assert\Assert;

class Foo
{
	public function uuid(string $s): void
	{
		Assert::stringNotEmpty($s);
		Assert::uuid($s);
	}

}
