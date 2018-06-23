<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use PHPStan\Rules\Rule;

class AssertTypeSpecifyingExtensionTest extends \PHPStan\Testing\RuleTestCase
{

	protected function getRule(): Rule
	{
		return new VariableTypeReportingRule();
	}

	/**
	 * @return \PHPStan\Type\StaticMethodTypeSpecifyingExtension[]
	 */
	protected function getStaticMethodTypeSpecifyingExtensions(): array
	{
		return [
			new AssertTypeSpecifyingExtension(),
		];
	}

	public function testExtension(): void
	{
		$this->analyse([__DIR__ . '/data/data.php'], [
			[
				'Variable $a is: mixed',
				12,
			],
			[
				'Variable $a is: int',
				15,
			],
			[
				'Variable $b is: int|null',
				18,
			],
			[
				'Variable $c is: array<int>',
				21,
			],
			[
				'Variable $d is: iterable<int>',
				24,
			],
			[
				'Variable $e is: string',
				27,
			],
			[
				'Variable $f is: float',
				30,
			],
			[
				'Variable $g is: float|int|string',
				33,
			],
			[
				'Variable $h is: bool',
				36,
			],
			[
				'Variable $i is: bool|float|int|string',
				39,
			],
			[
				'Variable $j is: object',
				42,
			],
			[
				'Variable $k is: resource',
				45,
			],
			[
				'Variable $l is: callable',
				48,
			],
			[
				'Variable $m is: array',
				51,
			],
			[
				'Variable $n is: array|Traversable',
				54,
			],
			[
				'Variable $o is: array|Countable',
				57,
			],
			[
				'Variable $p is: PHPStan\Type\WebMozartAssert\Foo',
				60,
			],
			[
				'Variable $q is: PHPStan\Type\WebMozartAssert\Foo',
				65,
			],
			[
				'Variable $r is: true',
				68,
			],
			[
				'Variable $s is: false',
				71,
			],
			[
				'Variable $t is: null',
				74,
			],
			[
				'Variable $u is: int',
				77,
			],
			[
				'Variable $v is: array<PHPStan\Type\WebMozartAssert\Foo>',
				82,
			],
			[
				'Variable $w is: array<int>',
				87,
			],
			[
				'Variable $x is: 1',
				90,
			],
			[
				'Variable $y is: -1|1',
				98,
			],
			[
				'Variable $y is: -1',
				100,
			],
			[
				'Variable $z is: array(1, -2|2, -3|3)',
				107,
			],
			[
				'Variable $aa is: PHPStan\Type\WebMozartAssert\Foo',
				110,
			],
			[
				'Variable $ab is: array<PHPStan\Type\WebMozartAssert\Foo>',
				113,
			],
		]);
	}

}
