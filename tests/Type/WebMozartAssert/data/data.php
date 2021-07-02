<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;

class Foo
{

	public function doFoo($a, $b, array $c, iterable $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n, $o, $p, $r, $s, ?int $t, ?int $u, $x, $aa, array $ab, $ac, $ad, $ae, $af, $ag, array $ah, $ai, $al, $am, $an)
	{
		$a;

		Assert::integer($a);
		$a;

		Assert::nullOrInteger($b);
		$b;

		Assert::allInteger($c);
		$c;

		Assert::allInteger($d);
		$d;

		Assert::string($e);
		$e;

		Assert::float($f);
		$f;

		Assert::numeric($g);
		$g;

		Assert::boolean($h);
		$h;

		Assert::scalar($i);
		$i;

		Assert::object($j);
		$j;

		Assert::resource($k);
		$k;

		Assert::isCallable($l);
		$l;

		Assert::isArray($m);
		$m;

		Assert::isIterable($n);
		$n;

		Assert::isCountable($o);
		$o;

		Assert::isInstanceOf($p, self::class);
		$p;

		/** @var Foo|Bar $q */
		$q = doFoo();
		Assert::notInstanceOf($q, Bar::class);
		$q;

		Assert::true($r);
		$r;

		Assert::false($s);
		$s;

		Assert::null($t);
		$t;

		Assert::notNull($u);
		$u;

		/** @var (Foo|Bar)[] $v */
		$v = doFoo();
		Assert::allNotInstanceOf($v, Bar::class);
		$v;

		/** @var (int|null)[] $w */
		$w = doFoo();
		Assert::allNotNull($w);
		$w;

		Assert::same($x, 1);
		$x;

		if (doFoo()) {
			$y = 1;
		} else {
			$y = -1;
		}

		$y;
		Assert::notSame($y, 1);
		$y;

		$z = [1, 2, 3];
		if (doFoo()) {
			$z = [-1, -2, -3];
		}
		Assert::allNotSame($z, -1);
		$z;

		Assert::subclassOf($aa, self::class);
		$aa;

		Assert::allSubclassOf($ab, self::class);
		$ab;

		Assert::stringNotEmpty($ac);
		$ac;

		Assert::integerish($ad);
		$ad;

		Assert::implementsInterface($ae, Baz::class);
		$ae;

		/** @var int|false $af */
		Assert::notFalse($af);
		$af;

		/** @var array{foo?: string, bar?: string} $things */
		$things = doFoo();
		Assert::keyExists($things, 'foo');
		$things;

		Assert::classExists($ag);
		$ag;

		if (rand(0, 1)) {
			$ah = false;
		}

		Assert::allIsInstanceOf($ah, \stdClass::class);
		$ah;

		Assert::isList($ai);
		$ai;
		Assert::allString($ai);
		$ai;

		/** @var int[] $aj */
		$aj = doFoo();
		Assert::minCount($aj, 1);
		$ak = array_pop($aj);
		$ak;

		Assert::inArray($al, ['foo', 'bar']);
		$al;

		Assert::nullOrInArray($am, ['foo', 'bar']);
		$am;

		Assert::oneOf($an, [1, 2]);
		$an;
    }

}

class Bar
{

}

interface Baz
{

}
