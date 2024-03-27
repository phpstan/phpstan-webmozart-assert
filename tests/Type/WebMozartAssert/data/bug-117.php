<?php

declare(strict_types=1);

namespace WebmozartAssertBug117;

use Webmozart\Assert\Assert;
use function PHPStan\Testing\assertType;

class HelloWorld
{
	/**
	 * @param mixed[] $requestData
	 *
	 * @return array{
	 *     accountId: int,
	 *     errorColor: string|null,
	 *     theme: array{
	 *         backgroundColor: string|null,
	 *         textColor: string|null,
	 *         headerImage: array{id: int}|null,
	 *     },
	 * }
	 */
	public function getData(int $accountId, array $requestData): array
	{
		Assert::keyExists($requestData, 'errorColor');
		Assert::nullOrString($requestData['errorColor']);

		Assert::keyExists($requestData, 'theme');
		Assert::isArray($requestData['theme']);

		Assert::keyExists($requestData['theme'], 'headerImage');
		Assert::nullOrIsArray($requestData['theme']['headerImage']);

		if (null !== $requestData['theme']['headerImage']) {
			Assert::keyExists($requestData['theme']['headerImage'], 'id');
			Assert::integer($requestData['theme']['headerImage']['id']);
		}

		Assert::keyExists($requestData, 'theme', 'backgroundColor');
		Assert::nullOrString($requestData['theme']['backgroundColor']);

		Assert::keyExists($requestData, 'theme', 'textColor');
		Assert::nullOrString($requestData['theme']['textColor']);

		$requestData['accountId'] = $accountId;

		assertType("hasOffsetValue('accountId', int)&hasOffsetValue('errorColor', string|null)&hasOffsetValue('theme', array&hasOffsetValue('backgroundColor', string|null)&hasOffsetValue('headerImage', (array&hasOffsetValue('id', int))|null)&hasOffsetValue('textColor', string|null))&non-empty-array", $requestData);

		return $requestData;
	}
}
