<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use PHPStan\Rules\Comparison\ImpossibleCheckTypeStaticMethodCallRule;
use PHPStan\Rules\Rule;

/**
 * @extends \PHPStan\Testing\RuleTestCase<ImpossibleCheckTypeStaticMethodCallRule>
 */
class ImpossibleCheckTypeMethodCallRuleTest extends \PHPStan\Testing\RuleTestCase
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
		]);
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
