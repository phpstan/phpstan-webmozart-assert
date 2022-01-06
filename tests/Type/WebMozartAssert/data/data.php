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

		Assert::true($r);
		\PHPStan\Testing\assertType('true', $r);

		Assert::false($s);
		\PHPStan\Testing\assertType('false', $s);

		Assert::null($t);
		\PHPStan\Testing\assertType('null', $t);

		Assert::notNull($u);
		\PHPStan\Testing\assertType('int', $u);

		/** @var (Foo|Bar)[] $v */
		$v = doFoo();
		Assert::allNotInstanceOf($v, Bar::class);
		\PHPStan\Testing\assertType('array<PHPStan\Type\WebMozartAssert\Foo>', $v);

		/** @var (int|null)[] $w */
		$w = doFoo();
		Assert::allNotNull($w);
		\PHPStan\Testing\assertType('array<int>', $w);

		Assert::same($x, 1);
		\PHPStan\Testing\assertType('1', $x);

		if (doFoo()) {
			$y = 1;
		} else {
			$y = -1;
		}

		\PHPStan\Testing\assertType('-1|1', $y);
		Assert::notSame($y, 1);
		\PHPStan\Testing\assertType('-1', $y);

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

		/** @var int|false $af */
		Assert::notFalse($af);
		\PHPStan\Testing\assertType('int', $af);

		/** @var array{foo?: string, bar?: string} $things */
		$things = doFoo();
		Assert::keyExists($things, 'foo');
		\PHPStan\Testing\assertType('array{foo: string, bar?: string}', $things);

		Assert::classExists($ag);
		\PHPStan\Testing\assertType('class-string', $ag);

		if (rand(0, 1)) {
			$ah = false;
		}

		Assert::allIsInstanceOf($ah, \stdClass::class);
		\PHPStan\Testing\assertType('array<stdClass>', $ah);

		Assert::isList($ai);
		\PHPStan\Testing\assertType('array', $ai);
		Assert::allString($ai);
		\PHPStan\Testing\assertType('array<string>', $ai);

		/** @var int[] $aj */
		$aj = doFoo();
		Assert::minCount($aj, 1);
		$ak = array_pop($aj);
		\PHPStan\Testing\assertType('int', $ak);

		Assert::inArray($al, ['foo', 'bar']);
		\PHPStan\Testing\assertType('\'bar\'|\'foo\'', $al);

		Assert::nullOrInArray($am, ['foo', 'bar']);
		\PHPStan\Testing\assertType('\'bar\'|\'foo\'|null', $am);

		Assert::oneOf($an, [1, 2]);
		\PHPStan\Testing\assertType('1|2', $an);

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
