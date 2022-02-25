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
		]);
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
