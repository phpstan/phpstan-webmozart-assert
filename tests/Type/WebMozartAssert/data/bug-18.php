<?php declare(strict_types=1);

namespace Bug18;

use Webmozart\Assert\Assert;
use function PHPStan\Testing\assertType;

class MyThingFactory
{
	public function make(string $thing)
	{
		Assert::implementsInterface($thing, SomeDto::class);

		assertType('class-string<Bug18\SomeDto>', $thing);
	}
}

interface SomeDto {}
