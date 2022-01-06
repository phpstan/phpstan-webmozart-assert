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

		Assert::subclassOf($aa, self::class);
		\PHPStan\Testing\assertType('class-string<PHPStan\Type\WebMozartAssert\TypeInferenceTest>|PHPStan\Type\WebMozartAssert\TypeInferenceTest', $aa);

		Assert::implementsInterface($ae, Baz::class);
		\PHPStan\Testing\assertType('PHPStan\Type\WebMozartAssert\Baz', $ae);

		/** @var array{foo?: string, bar?: string} $things */
		$things = doFoo();
		Assert::keyExists($things, 'foo');
		\PHPStan\Testing\assertType('array{foo: string, bar?: string}', $things);

		Assert::classExists($ag);
		\PHPStan\Testing\assertType('class-string', $ag);

		if (rand(0, 1)) {
			$ah = false;
		}

		Assert::isList($ai);
		\PHPStan\Testing\assertType('array', $ai);

		/** @var int[] $aj */
		$aj = doFoo();
		Assert::minCount($aj, 1);
		$ak = array_pop($aj);
		\PHPStan\Testing\assertType('int', $ak);

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

		/** @var int[] $at */
		$at = doFoo();
		Assert::count($at, 1);
		$au = array_pop($at);
		$av = array_pop($at);
		\PHPStan\Testing\assertType('int', $au);
		\PHPStan\Testing\assertType('int|null', $av);
    }

}

class Bar
{

}

interface Baz
{

}
