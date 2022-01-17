<?php

declare(strict_types=1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;

final class Bug85
{

	public function foo(string $cityCode): void
	{
		Assert::length($cityCode, 3);
		Assert::upper($cityCode);
	}

	/**
	 * @param mixed $url
	 */
	function bar($url): void
	{
		Assert::stringNotEmpty($url);
		Assert::contains($url, '/');
		Assert::startsWith($url, 'https://github.com/');
	}

	public function baz(string $s): void
	{
		Assert::stringNotEmpty($s);
		Assert::uuid($s);
	}

}
