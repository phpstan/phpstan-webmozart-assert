<?php

declare(strict_types=1);

namespace PHPStan\WebMozartIntegration\Bug85;

use Webmozart\Assert\Assert;

final class Bug85
{

	public function foo(string $cityCode): void
	{
		Assert::length($cityCode, 3);
		Assert::upper($cityCode);

		\PHPStan\Testing\assertType('non-empty-string', $cityCode);
	}

	/**
	 * @param mixed $url
	 */
	function bar($url): void
	{
		Assert::stringNotEmpty($url);
		Assert::contains($url, '/');
		Assert::startsWith($url, 'https://github.com/');

		\PHPStan\Testing\assertType('non-empty-string', $url);
	}

}
