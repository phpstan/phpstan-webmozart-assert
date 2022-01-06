<?php declare(strict_types=1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;

class ObjectTest
{

	public function classExists($a): void
	{
		Assert::classExists($a);
		\PHPStan\Testing\assertType('class-string', $a);
	}

	public function subclassOf($a): void
	{
		Assert::subclassOf($a, self::class);
		\PHPStan\Testing\assertType('class-string<PHPStan\Type\WebMozartAssert\ObjectTest>|PHPStan\Type\WebMozartAssert\ObjectTest', $a);
	}

	public function interfaceExists($a): void
	{
		Assert::interfaceExists($a);
		\PHPStan\Testing\assertType('class-string', $a);
	}

	public function implementsInterface($a): void
	{
		Assert::implementsInterface($a, ObjectFoo::class);
		\PHPStan\Testing\assertType('PHPStan\Type\WebMozartAssert\ObjectFoo', $a);
	}

	public function propertyExists(object $a): void
	{
		Assert::propertyExists($a, 'foo');
		\PHPStan\Testing\assertType('object&hasProperty(foo)', $a);
	}

	public function methodExists(object $a): void
	{
		Assert::methodExists($a, 'foo');
		\PHPStan\Testing\assertType('object&hasMethod(foo)', $a);
	}

}

interface ObjectFoo
{

}
