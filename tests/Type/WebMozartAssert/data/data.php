<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;

class TypeInferenceTest
{

	public function doFoo($a, $b, array $c, iterable $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n, $o, $p, $r, $s, ?int $t, ?int $u, $x, $aa, array $ab, $ac, $ad, $ae, $af, $ag, array $ah, $ai, $al, $am, $an, $ao, $ap, $aq, $ar, $as)
	{
		\PHPStan\Testing\assertType('mixed', $a);

		Assert::nullOrInteger($b);
		\PHPStan\Testing\assertType('int|null', $b);

		Assert::allInteger($c);
		\PHPStan\Testing\assertType('array<int>', $c);

		Assert::allInteger($d);
		\PHPStan\Testing\assertType('iterable<int>', $d);

		/** @var (Foo|Bar)[] $v */
		$v = doFoo();
		Assert::allNotInstanceOf($v, Bar::class);
		\PHPStan\Testing\assertType('array<PHPStan\Type\WebMozartAssert\Foo>', $v);

		/** @var (int|null)[] $w */
		$w = doFoo();
		Assert::allNotNull($w);
		\PHPStan\Testing\assertType('array<int>', $w);

		$z = [1, 2, 3];
		if (doFoo()) {
			$z = [-1, -2, -3];
		}
		Assert::allNotSame($z, -1);
		\PHPStan\Testing\assertType('array{1, -2|2, -3|3}', $z);

		Assert::subclassOf($aa, self::class);
		\PHPStan\Testing\assertType('class-string<PHPStan\Type\WebMozartAssert\TypeInferenceTest>|PHPStan\Type\WebMozartAssert\TypeInferenceTest', $aa);

		Assert::allSubclassOf($ab, self::class);
		// should array<PHPStan\Type\WebMozartAssert\Foo>
		\PHPStan\Testing\assertType('array<*NEVER*>', $ab);

		Assert::implementsInterface($ae, Baz::class);
		\PHPStan\Testing\assertType('PHPStan\Type\WebMozartAssert\Baz', $ae);

		Assert::classExists($ag);
		\PHPStan\Testing\assertType('class-string', $ag);

		if (rand(0, 1)) {
			$ah = false;
		}

		Assert::allIsInstanceOf($ah, \stdClass::class);
		\PHPStan\Testing\assertType('array<stdClass>', $ah);

		/** @var array $ai */
		Assert::allString($ai);
		\PHPStan\Testing\assertType('array<string>', $ai);

		Assert::nullOrInArray($am, ['foo', 'bar']);
		\PHPStan\Testing\assertType('\'bar\'|\'foo\'|null', $am);

		/** @var object $ao */
		Assert::methodExists($ao, 'foo');
		\PHPStan\Testing\assertType('object&hasMethod(foo)', $ao);

		/** @var object $ap */
		Assert::propertyExists($ap, 'foo');
		\PHPStan\Testing\assertType('object&hasProperty(foo)', $ap);

		Assert::isArrayAccessible($aq);
		\PHPStan\Testing\assertType('array|ArrayAccess', $aq);

		Assert::interfaceExists($ag);
		\PHPStan\Testing\assertType('class-string', $ag);
    }

}

class Bar
{

}

interface Baz
{

}
