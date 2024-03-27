<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use PHPStan\Rules\Methods\ReturnTypeRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/** @extends RuleTestCase<ReturnTypeRule> */
class MethodReturnTypeRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return self::getContainer()->getByType(ReturnTypeRule::class);
	}

	public function testBug117(): void
	{
		$this->analyse([__DIR__ . '/data/bug-117.php'], []);
	}

}
