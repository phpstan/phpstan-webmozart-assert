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
				'Call to static method Webmozart\Assert\Assert::stringNotEmpty() with null will always evaluate to false.',
				41,
				'Because the type is coming from a PHPDoc, you can turn off this check by setting <fg=cyan>treatPhpDocTypesAsCertain: false</> in your <fg=cyan>%configurationFile%</>.',
			],
			[
				'Call to static method Webmozart\Assert\Assert::stringNotEmpty() with non-empty-string will always evaluate to true.',
				44,
				'Because the type is coming from a PHPDoc, you can turn off this check by setting <fg=cyan>treatPhpDocTypesAsCertain: false</> in your <fg=cyan>%configurationFile%</>.',
			],
			[
				'Call to static method Webmozart\Assert\Assert::stringNotEmpty() with non-empty-string will always evaluate to true.',
				46,
				'Because the type is coming from a PHPDoc, you can turn off this check by setting <fg=cyan>treatPhpDocTypesAsCertain: false</> in your <fg=cyan>%configurationFile%</>.',
			],
			[
				'Call to static method Webmozart\Assert\Assert::nullOrStringNotEmpty() with non-empty-string will always evaluate to true.',
				49,
				'Because the type is coming from a PHPDoc, you can turn off this check by setting <fg=cyan>treatPhpDocTypesAsCertain: false</> in your <fg=cyan>%configurationFile%</>.',
			],
			[
				'Call to static method Webmozart\Assert\Assert::nullOrStringNotEmpty() with non-empty-string|null will always evaluate to true.',
				53,
				'Because the type is coming from a PHPDoc, you can turn off this check by setting <fg=cyan>treatPhpDocTypesAsCertain: false</> in your <fg=cyan>%configurationFile%</>.',
			],
			[
				'Call to static method Webmozart\Assert\Assert::allCount() with array<non-empty-array> and 2 will always evaluate to true.',
				76,
			],
			[
				'Call to static method Webmozart\Assert\Assert::uuid() with non-empty-string will always evaluate to true.',
				84,
			],
			[
				'Call to static method Webmozart\Assert\Assert::contains() with non-empty-string and \'foo\' will always evaluate to true.',
				88,
			],
			[
				'Call to static method Webmozart\Assert\Assert::allUuid() with array<non-empty-string> will always evaluate to true.',
				94,
			],
			[
				'Call to static method Webmozart\Assert\Assert::allContains() with array<non-empty-string> and \'foo\' will always evaluate to true.',
				98,
			],
			[
				'Call to static method Webmozart\Assert\Assert::implementsInterface() with class-string<WebmozartAssertImpossibleCheck\Bar>|WebmozartAssertImpossibleCheck\Bar and \'WebmozartAssertImpossibleCheck\\\Bar\' will always evaluate to true.',
				105,
			],
			[
				'Call to static method Webmozart\Assert\Assert::implementsInterface() with class-string<WebmozartAssertImpossibleCheck\Bar> and \'WebmozartAssertImpossibleCheck\\\Bar\' will always evaluate to true.',
				108,
			],
			[
				'Call to static method Webmozart\Assert\Assert::implementsInterface() with mixed and \'WebmozartAssertImpossibleCheck\\\Foo\' will always evaluate to false.',
				111,
			],
			[
				'Call to static method Webmozart\Assert\Assert::isInstanceOf() with Exception and class-string<Exception> will always evaluate to true.',
				119,
			],
		]);
	}

	public function testEqNotEq(): void
	{
		$this->analyse([__DIR__ . '/data/impossible-check-eq-not-eq.php'], [
			[
				'Call to static method Webmozart\Assert\Assert::eq() with stdClass and stdClass will always evaluate to true.',
				14,
			],
			[
				'Call to static method Webmozart\Assert\Assert::notEq() with stdClass and stdClass will always evaluate to false.',
				15,
			],
			[
				'Call to static method Webmozart\Assert\Assert::eq() with 1 and \'1\' will always evaluate to true.',
				37,
			],
			[
				'Call to static method Webmozart\Assert\Assert::notEq() with 1 and \'1\' will always evaluate to false.',
				38,
			],
			[
				'Call to static method Webmozart\Assert\Assert::eq() with 1 and true will always evaluate to true.',
				39,
			],
			[
				'Call to static method Webmozart\Assert\Assert::notEq() with 1 and true will always evaluate to false.',
				40,
			],
			[
				'Call to static method Webmozart\Assert\Assert::eq() with \'php\' and true will always evaluate to true.',
				41,
			],
			[
				'Call to static method Webmozart\Assert\Assert::notEq() with \'php\' and true will always evaluate to false.',
				42,
			],
			[
				'Call to static method Webmozart\Assert\Assert::eq() with \'\' and false will always evaluate to true.',
				43,
			],
			[
				'Call to static method Webmozart\Assert\Assert::notEq() with \'\' and false will always evaluate to false.',
				44,
			],
			[
				'Call to static method Webmozart\Assert\Assert::eq() with 1 and 1 will always evaluate to true.',
				46,
			],
			[
				'Call to static method Webmozart\Assert\Assert::notEq() with 1 and 1 will always evaluate to false.',
				47,
			],
			[
				'Call to static method Webmozart\Assert\Assert::eq() with true and true will always evaluate to true.',
				48,
			],
			[
				'Call to static method Webmozart\Assert\Assert::notEq() with true and true will always evaluate to false.',
				49,
			],
			[
				'Call to static method Webmozart\Assert\Assert::eq() with \'php\' and \'php\' will always evaluate to true.',
				50,
			],
			[
				'Call to static method Webmozart\Assert\Assert::notEq() with \'php\' and \'php\' will always evaluate to false.',
				51,
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

	public function testBug17(): void
	{
		$this->analyse([__DIR__ . '/data/bug-17.php'], [
			[
				'Call to static method Webmozart\Assert\Assert::implementsInterface() with \'DateTime\' and \'DateTimeInterface\' will always evaluate to true.',
				9,
			],
		]);
	}

	public function testBug18(): void
	{
		$this->analyse([__DIR__ . '/data/bug-18.php'], []);
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

	public function testBug118(): void
	{
		$this->analyse([__DIR__ . '/data/bug-118.php'], []);
	}

	public function testBug119(): void
	{
		$this->analyse([__DIR__ . '/data/bug-119.php'], []);
	}

	public function testBug130(): void
	{
		$this->analyse([__DIR__ . '/data/bug-130.php'], []);
	}

	public function testBug150(): void
	{
		$this->analyse([__DIR__ . '/data/bug-150.php'], []);
	}

	public static function getAdditionalConfigFiles(): array
	{
		return [
			__DIR__ . '/../../../vendor/phpstan/phpstan-strict-rules/rules.neon',
			__DIR__ . '/../../../extension.neon',
		];
	}

}
