<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use PHPStan\Rules\Comparison\ImpossibleCheckTypeHelper;
use PHPStan\Rules\Comparison\ImpossibleCheckTypeStaticMethodCallRule;
use PHPStan\Rules\Rule;

/**
 * @extends \PHPStan\Testing\RuleTestCase<ImpossibleCheckTypeStaticMethodCallRule>
 */
class ImpossibleCheckTypeMethodCallRuleTest extends \PHPStan\Testing\RuleTestCase
{

	protected function getRule(): Rule
	{
		return new ImpossibleCheckTypeStaticMethodCallRule(new ImpossibleCheckTypeHelper($this->createBroker(), $this->getTypeSpecifier(), [], true), true, true);
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
		$this->analyse([__DIR__ . '/data/impossible-check.php'], [
			[
				'Call to static method Webmozart\Assert\Assert::stringNotEmpty() with \'\' will always evaluate to false.',
				13,
			],
		]);
	}

}
