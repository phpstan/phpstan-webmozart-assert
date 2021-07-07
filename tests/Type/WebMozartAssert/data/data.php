<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;

class Foo
{

	public function doFoo($a, $b, array $c, iterable $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n, $o, $p, $r, $s, ?int $t, ?int $u, $x, $aa, array $ab, $ac, $ad, $ae, $af, $ag, array $ah, $ai, $al, $am, $an, $ao, $ap, $aq)
	{
		\PHPStan\Testing\assertType('mixed', $a);

		Assert::integer($a);
		\PHPStan\Testing\assertType('int', $a);

		Assert::nullOrInteger($b);
		\PHPStan\Testing\assertType('int|null', $b);

		Assert::allInteger($c);
		\PHPStan\Testing\assertType('array<int>', $c);

		Assert::allInteger($d);
		\PHPStan\Testing\assertType('iterable<int>', $d);

		Assert::string($e);
		\PHPStan\Testing\assertType('string', $e);

		Assert::float($f);
		\PHPStan\Testing\assertType('float', $f);

		Assert::numeric($g);
		\PHPStan\Testing\assertType('float|int|(string&numeric)', $g);

		Assert::boolean($h);
		\PHPStan\Testing\assertType('bool', $h);

		Assert::scalar($i);
		\PHPStan\Testing\assertType('bool|float|int|string', $i);

		Assert::object($j);
		\PHPStan\Testing\assertType('object', $j);

		Assert::resource($k);
		\PHPStan\Testing\assertType('resource', $k);

		Assert::isCallable($l);
		\PHPStan\Testing\assertType('callable(): mixed', $l);

		Assert::isArray($m);
		\PHPStan\Testing\assertType('array', $m);

		Assert::isIterable($n);
		\PHPStan\Testing\assertType('array|Traversable', $n);

		Assert::isCountable($o);
		\PHPStan\Testing\assertType('array|Countable', $o);

		Assert::isInstanceOf($p, self::class);
		\PHPStan\Testing\assertType('PHPStan\Type\WebMozartAssert\Foo', $p);

		/** @var Foo|Bar $q */
		$q = doFoo();
		Assert::notInstanceOf($q, Bar::class);
		\PHPStan\Testing\assertType('PHPStan\Type\WebMozartAssert\Foo', $q);

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
		\PHPStan\Testing\assertType('array(1, -2|2, -3|3)', $z);

		Assert::subclassOf($aa, self::class);
		\PHPStan\Testing\assertType('class-string<PHPStan\Type\WebMozartAssert\Foo>|PHPStan\Type\WebMozartAssert\Foo', $aa);

		Assert::allSubclassOf($ab, self::class);
		// should array<PHPStan\Type\WebMozartAssert\Foo>
		\PHPStan\Testing\assertType('array<*NEVER*>', $ab);

		Assert::stringNotEmpty($ac);
		\PHPStan\Testing\assertType('non-empty-string', $ac);

		Assert::integerish($ad);
		\PHPStan\Testing\assertType('float|int|(string&numeric)', $ad);

		Assert::implementsInterface($ae, Baz::class);
		\PHPStan\Testing\assertType('PHPStan\Type\WebMozartAssert\Baz', $ae);

		/** @var int|false $af */
		Assert::notFalse($af);
		\PHPStan\Testing\assertType('int', $af);

		/** @var array{foo?: string, bar?: string} $things */
		$things = doFoo();
		Assert::keyExists($things, 'foo');
		\PHPStan\Testing\assertType('array(\'foo\' => string, ?\'bar\' => string)', $things);

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
    }

}

class Bar
{

}

interface Baz
{

}
