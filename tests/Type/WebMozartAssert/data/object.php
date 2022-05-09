<?php declare(strict_types=1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;
use function PHPStan\Testing\assertType;

class ObjectTest
{

	public function classExists($a, $b): void
	{
		Assert::classExists($a);
		assertType('class-string', $a);

		Assert::nullOrClassExists($b);
		assertType('class-string|null', $b);
	}

	public function subclassOf($a, $b): void
	{
		Assert::subclassOf($a, self::class);
		assertType('class-string<PHPStan\Type\WebMozartAssert\ObjectTest>|PHPStan\Type\WebMozartAssert\ObjectTest', $a);

		Assert::nullOrSubclassOf($b, self::class);
		assertType('class-string<PHPStan\Type\WebMozartAssert\ObjectTest>|PHPStan\Type\WebMozartAssert\ObjectTest|null', $b);
	}

	public function interfaceExists($a, $b): void
	{
		Assert::interfaceExists($a);
		assertType('class-string', $a);

		Assert::nullOrInterfaceExists($b);
		assertType('class-string|null', $b);
	}

	public function implementsInterface($a, $b): void
	{
		Assert::implementsInterface($a, ObjectFoo::class);
		assertType('PHPStan\Type\WebMozartAssert\ObjectFoo', $a);

		Assert::nullOrImplementsInterface($b, ObjectFoo::class);
		assertType('PHPStan\Type\WebMozartAssert\ObjectFoo|null', $b);
	}

	public function propertyExists(object $a): void
	{
		Assert::propertyExists($a, 'foo');
		assertType('object&hasProperty(foo)', $a);
	}

	public function methodExists(object $a): void
	{
		Assert::methodExists($a, 'foo');
		assertType('object&hasMethod(foo)', $a);
	}

}

interface ObjectFoo
{

}
