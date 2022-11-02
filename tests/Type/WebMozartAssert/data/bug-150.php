<?php declare(strict_types=1);

namespace WebmozartAssertBug150;

use Webmozart\Assert\Assert;
use function PHPStan\Testing\assertType;

class Bug150
{

	public function doFoo($data): void
	{
		Assert::isArray($data);
		Assert::keyExists($data, 'sniffs');
		Assert::isArray($data['sniffs']);
		assertType("array&hasOffsetValue('sniffs', array)", $data);

		foreach ($data['sniffs'] as $sniffName) {
			Assert::string($sniffName);
			Assert::classExists($sniffName);
			assertType('class-string', $sniffName);
			Assert::implementsInterface($sniffName, SniffInterface::class);
			assertType('class-string<WebmozartAssertBug150\SniffInterface>', $sniffName);
		}
	}

}

interface SniffInterface {}
