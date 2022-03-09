<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use PHPStan\Rules\Comparison\ImpossibleCheckTypeStaticMethodCallRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ImpossibleCheckTypeStaticMethodCallRule>
 */
class ImpossibleCheckTypeMethodCallRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return self::getContainer()->getByType(ImpossibleCheckTypeStaticMethodCallRule::class);
	}

	public function testExtension(): void
	{
		$this->analyse([__DIR__ . '/data/impossible-check.php'], [
			[
				'Call to static method Webmozart\Assert\Assert::stringNotEmpty() with \'\' will always evaluate to false.',
				13,
			],
			[
				'Call to static method Webmozart\Assert\Assert::isInstanceOf() with WebmozartAssertImpossibleCheck\Bar and \'WebmozartAssertImpossibleCheck\\\Bar\' will always evaluate to true.',
				21,
			],
			[
				'Call to static method Webmozart\Assert\Assert::isInstanceOf() with WebmozartAssertImpossibleCheck\Bar and WebmozartAssertImpossibleCheck\Bar will always evaluate to true.',
				22,
			],
			[
				'Call to static method Webmozart\Assert\Assert::nullOrIsInstanceOf() with WebmozartAssertImpossibleCheck\Bar and \'WebmozartAssertImpossibleCheck\\\Bar\' will always evaluate to true.',
				23,
			],
			[
				'Call to static method Webmozart\Assert\Assert::allIsInstanceOf() with array<WebmozartAssertImpossibleCheck\Bar> and \'WebmozartAssertImpossibleCheck\\\Bar\' will always evaluate to true.',
				26,
			],
			[
				'Call to static method Webmozart\Assert\Assert::notInstanceOf() with WebmozartAssertImpossibleCheck\Bar and \'WebmozartAssertImpossibleCheck\\\Bar\' will always evaluate to false.',
				32,
			],
			[
				'Call to static method Webmozart\Assert\Assert::stringNotEmpty() with non-empty-string will always evaluate to true.',
				44,
			],
			[
				'Call to static method Webmozart\Assert\Assert::stringNotEmpty() with non-empty-string will always evaluate to true.',
				46,
			],
			[
				'Call to static method Webmozart\Assert\Assert::nullOrStringNotEmpty() with non-empty-string will always evaluate to true.',
				49,
			],
			[
				'Call to static method Webmozart\Assert\Assert::nullOrStringNotEmpty() with non-empty-string|null will always evaluate to true.',
				53,
			],
		]);
	}

	public function testEqNotEq(): void
	{
		$this->analyse([__DIR__ . '/data/impossible-check-eq-not-eq.php'], [
			[
				'Call to static method Webmozart\Assert\Assert::eq() with stdClass and stdClass will always evaluate to true.',
				11,
			],
			[
				'Call to static method Webmozart\Assert\Assert::notEq() with stdClass and stdClass will always evaluate to false.',
				12,
			],
			[
				'Call to static method Webmozart\Assert\Assert::eq() with stdClass and stdClass will always evaluate to true.',
				30,
			],
			[
				'Call to static method Webmozart\Assert\Assert::eq() with stdClass and null will always evaluate to false.',
				33,
			],
			[
				'Call to static method Webmozart\Assert\Assert::notEq() with stdClass and null will always evaluate to true.',
				34,
			],
		]);
	}

	public function testBug8(): void
	{
		$this->analyse([__DIR__ . '/data/bug-8.php'], [
			[
				'Call to static method Webmozart\Assert\Assert::numeric() with numeric-string will always evaluate to true.',
				15,
			],
			[
				'Call to static method Webmozart\Assert\Assert::numeric() with \'foo\' will always evaluate to false.',
				16,
			],
			[
				'Call to static method Webmozart\Assert\Assert::numeric() with \'17.19\' will always evaluate to true.',
				17,
			],
		]);
	}

	public function testBug32(): void
	{
		$this->analyse([__DIR__ . '/data/bug-32.php'], []);
	}

	public function testBug33(): void
	{
		$this->analyse([__DIR__ . '/data/bug-33.php'], []);
	}

	public function testBug68(): void
	{
		$this->analyse([__DIR__ . '/data/bug-68.php'], []);
	}

	public function testBug85(): void
	{
		$this->analyse([__DIR__ . '/data/bug-85.php'], []);
	}

	public static function getAdditionalConfigFiles(): array
	{
		return [
			__DIR__ . '/../../../vendor/phpstan/phpstan-strict-rules/rules.neon',
			__DIR__ . '/../../../extension.neon',
		];
	}

}
