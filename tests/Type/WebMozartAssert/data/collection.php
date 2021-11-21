<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;

class Foo
{

	/**
	 * @param array<array{id?: int}> $a
	 * @param array<int, array<string, mixed>> $b
	 *
	 */
	public function allKeyExists(array $a, array $b, array $c): void
	{
		Assert::allKeyExists($a, 'id');
		\PHPStan\Testing\assertType('array<array{id: int}>', $a);

		Assert::allKeyExists($b, 'id');
		\PHPStan\Testing\assertType('array<int, array<string, mixed>&hasOffset(\'id\')>', $b);

		Assert::allKeyExists($c, 'id');
		\PHPStan\Testing\assertType('array<array&hasOffset(\'id\')>', $c);
	}

}
