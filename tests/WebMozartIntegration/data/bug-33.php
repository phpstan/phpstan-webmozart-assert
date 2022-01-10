<?php

declare(strict_types=1);

namespace PHPStan\WebMozartIntegration\Bug33;

use Webmozart\Assert\Assert;

final class Bug33
{

	public function foo(?string $bar): void
	{
		Assert::nullOrStringNotEmpty($bar);
		\PHPStan\Testing\assertType('non-empty-string|null', $bar);
	}

}
