<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use PhpParser\Node;
use PHPStan\Analyser\Scope;

class VariableTypeReportingRule implements \PHPStan\Rules\Rule
{

	public function getNodeType(): string
	{
		return Node\Expr\Variable::class;
	}

	/**
	 * @param \PhpParser\Node\Expr\Variable $node
	 * @param \PHPStan\Analyser\Scope $scope
	 * @return string[] errors
	 */
	public function processNode(Node $node, Scope $scope): array
	{
		if (!is_string($node->name)) {
			return [];
		}
		if (!$scope->isInFirstLevelStatement()) {
			return [];
		};

		return [
			sprintf(
				'Variable $%s is: %s',
				$node->name,
				$scope->getType($node)->describe(\PHPStan\Type\VerbosityLevel::value())
			),
		];
	}

}
