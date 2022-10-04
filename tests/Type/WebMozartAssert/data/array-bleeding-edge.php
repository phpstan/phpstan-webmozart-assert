<?php declare(strict_types=1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;
use function PHPStan\Testing\assertType;

class ArrayBleedingEdgeTest
{

	public function isList($a, $b): void
	{
		Assert::isList($a);
		assertType('list<mixed>', $a);

		Assert::nullOrIsList($b);
		assertType('list<mixed>|null', $b);
	}

	public function isNonEmptyList($a, $b): void
	{
		Assert::isNonEmptyList($a);
		assertType('non-empty-list<mixed>', $a);

		Assert::nullOrIsNonEmptyList($b);
		assertType('non-empty-list<mixed>|null', $b);
	}

}
