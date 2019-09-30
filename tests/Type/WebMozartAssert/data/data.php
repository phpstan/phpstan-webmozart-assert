<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use Webmozart\Assert\Assert;

class Foo
{

	public function doFoo($a, $b, array $c, iterable $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n, $o, $p, $r, $s, ?int $t, ?int $u, $x, $aa, array $ab, $ac, $ad)
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

        Assert::implementsInterface($q, Baz::class);
        $q;
    }

}

class Bar implements Baz
{

}

interface Baz
{

}
