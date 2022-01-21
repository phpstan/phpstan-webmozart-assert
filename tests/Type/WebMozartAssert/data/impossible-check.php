<?php

namespace WebmozartAssertImpossibleCheck;

use Webmozart\Assert\Assert;

class Foo
{

	public function doFoo(string $s): void
	{
		Assert::stringNotEmpty($s);
		Assert::stringNotEmpty('');
	}

	/**
	 * @param mixed[] $b
	 */
	public function isInstanceOf(Bar $a, array $b): void
	{
		Assert::isInstanceOf($a, Bar::class);
		Assert::isInstanceOf($a, $a);
		Assert::nullOrIsInstanceOf($a, Bar::class);

		Assert::allIsInstanceOf($b, Bar::class);
		Assert::allIsInstanceOf($b, Bar::class);
	}

	public function notInstanceOf(Bar $a): void
	{
		Assert::notInstanceOf($a, Baz::class);
		Assert::notInstanceOf($a, Bar::class);
	}

}

interface Bar {};

class Baz {}
