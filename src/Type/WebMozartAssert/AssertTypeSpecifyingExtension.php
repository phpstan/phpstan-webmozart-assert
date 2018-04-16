<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\SpecifiedTypes;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Analyser\TypeSpecifierAwareExtension;
use PHPStan\Analyser\TypeSpecifierContext;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\IterableType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StaticMethodTypeSpecifyingExtension;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

class AssertTypeSpecifyingExtension implements StaticMethodTypeSpecifyingExtension, TypeSpecifierAwareExtension
{

	/** @var \Closure[] */
	private static $resolvers;

	/** @var \PHPStan\Analyser\TypeSpecifier */
	private $typeSpecifier;

	public function setTypeSpecifier(TypeSpecifier $typeSpecifier): void
	{
		$this->typeSpecifier = $typeSpecifier;
	}

	public function getClass(): string
	{
		return \Webmozart\Assert\Assert::class;
	}

	public function isStaticMethodSupported(
		MethodReflection $staticMethodReflection,
		StaticCall $node,
		TypeSpecifierContext $context
	): bool
	{
		if (substr($staticMethodReflection->getName(), 0, 6) === 'allNot') {
			$methods = [
				'allNotInstanceOf' => 2,
				'allNotNull' => 1,
				'allNotSame' => 2,
			];
			return array_key_exists($staticMethodReflection->getName(), $methods)
				&& count($node->args) >= $methods[$staticMethodReflection->getName()];
		}

		$trimmedName = self::trimName($staticMethodReflection->getName());
		$resolvers = self::getExpressionResolvers();

		if (!array_key_exists($trimmedName, $resolvers)) {
			return false;
		}

		$resolver = $resolvers[$trimmedName];
		$resolverReflection = new \ReflectionObject($resolver);

		return count($node->args) >= (count($resolverReflection->getMethod('__invoke')->getParameters()) - 1);
	}

	private static function trimName(string $name): string
	{
		if (substr($name, 0, 6) === 'nullOr') {
			$name = substr($name, 6);
		}
		if (substr($name, 0, 3) === 'all') {
			$name = substr($name, 3);
		}

		return lcfirst($name);
	}

	public function specifyTypes(
		MethodReflection $staticMethodReflection,
		StaticCall $node,
		Scope $scope,
		TypeSpecifierContext $context
	): SpecifiedTypes
	{
		if (substr($staticMethodReflection->getName(), 0, 6) === 'allNot') {
			return $this->handleAllNot(
				$staticMethodReflection->getName(),
				$node,
				$scope
			);
		}
		$expression = self::createExpression($scope, $staticMethodReflection->getName(), $node->args);
		if ($expression === null) {
			return new SpecifiedTypes([], []);
		}
		$specifiedTypes = $this->typeSpecifier->specifyTypesInCondition(
			$scope,
			$expression,
			TypeSpecifierContext::createTruthy()
		);

		if (substr($staticMethodReflection->getName(), 0, 3) === 'all') {
			if (count($specifiedTypes->getSureTypes()) > 0) {
				$sureTypes = $specifiedTypes->getSureTypes();
				reset($sureTypes);
				$exprString = key($sureTypes);
				$sureType = $sureTypes[$exprString];
				return $this->arrayOrIterable($scope, $sureType[0], $sureType[1]);
			}
			if (count($specifiedTypes->getSureNotTypes()) > 0) {
				throw new \PHPStan\ShouldNotHappenException();
			}
		}

		return $specifiedTypes;
	}

	/**
	 * @param Scope $scope
	 * @param string $name
	 * @param \PhpParser\Node\Arg[] $args
	 * @return \PhpParser\Node\Expr|null
	 */
	private static function createExpression(
		Scope $scope,
		string $name,
		array $args
	): ?\PhpParser\Node\Expr
	{
		$trimmedName = self::trimName($name);
		$resolvers = self::getExpressionResolvers();
		$resolver = $resolvers[$trimmedName];
		$expression = $resolver($scope, ...$args);
		if ($expression === null) {
			return null;
		}

		if (substr($name, 0, 6) === 'nullOr') {
			$expression = new \PhpParser\Node\Expr\BinaryOp\BooleanOr(
				$expression,
				new \PhpParser\Node\Expr\BinaryOp\Identical(
					$args[0]->value,
					new \PhpParser\Node\Expr\ConstFetch(new \PhpParser\Node\Name('null'))
				)
			);
		}

		return $expression;
	}

	/**
	 * @return \Closure[]
	 */
	private static function getExpressionResolvers(): array
	{
		if (self::$resolvers === null) {
			self::$resolvers = [
				'integer' => function (Scope $scope, Arg $value): ?\PhpParser\Node\Expr {
					return new \PhpParser\Node\Expr\FuncCall(
						new \PhpParser\Node\Name('is_int'),
						[$value]
					);
				},
				'string' => function (Scope $scope, Arg $value): ?\PhpParser\Node\Expr {
					return new \PhpParser\Node\Expr\FuncCall(
						new \PhpParser\Node\Name('is_string'),
						[$value]
					);
				},
				'float' => function (Scope $scope, Arg $value): ?\PhpParser\Node\Expr {
					return new \PhpParser\Node\Expr\FuncCall(
						new \PhpParser\Node\Name('is_float'),
						[$value]
					);
				},
				'numeric' => function (Scope $scope, Arg $value): ?\PhpParser\Node\Expr {
					return new \PhpParser\Node\Expr\FuncCall(
						new \PhpParser\Node\Name('is_numeric'),
						[$value]
					);
				},
				'boolean' => function (Scope $scope, Arg $value): ?\PhpParser\Node\Expr {
					return new \PhpParser\Node\Expr\FuncCall(
						new \PhpParser\Node\Name('is_bool'),
						[$value]
					);
				},
				'scalar' => function (Scope $scope, Arg $value): ?\PhpParser\Node\Expr {
					return new \PhpParser\Node\Expr\FuncCall(
						new \PhpParser\Node\Name('is_scalar'),
						[$value]
					);
				},
				'object' => function (Scope $scope, Arg $value): ?\PhpParser\Node\Expr {
					return new \PhpParser\Node\Expr\FuncCall(
						new \PhpParser\Node\Name('is_object'),
						[$value]
					);
				},
				'resource' => function (Scope $scope, Arg $value): ?\PhpParser\Node\Expr {
					return new \PhpParser\Node\Expr\FuncCall(
						new \PhpParser\Node\Name('is_resource'),
						[$value]
					);
				},
				'isCallable' => function (Scope $scope, Arg $value): ?\PhpParser\Node\Expr {
					return new \PhpParser\Node\Expr\FuncCall(
						new \PhpParser\Node\Name('is_callable'),
						[$value]
					);
				},
				'isArray' => function (Scope $scope, Arg $value): ?\PhpParser\Node\Expr {
					return new \PhpParser\Node\Expr\FuncCall(
						new \PhpParser\Node\Name('is_array'),
						[$value]
					);
				},
				'isIterable' => function (Scope $scope, Arg $expr): ?\PhpParser\Node\Expr {
					return new \PhpParser\Node\Expr\BinaryOp\BooleanOr(
						new \PhpParser\Node\Expr\FuncCall(
							new \PhpParser\Node\Name('is_array'),
							[$expr]
						),
						new \PhpParser\Node\Expr\Instanceof_(
							$expr->value,
							new \PhpParser\Node\Name(\Traversable::class)
						)
					);
				},
				'isCountable' => function (Scope $scope, Arg $expr): ?\PhpParser\Node\Expr {
					return new \PhpParser\Node\Expr\BinaryOp\BooleanOr(
						new \PhpParser\Node\Expr\FuncCall(
							new \PhpParser\Node\Name('is_array'),
							[$expr]
						),
						new \PhpParser\Node\Expr\Instanceof_(
							$expr->value,
							new \PhpParser\Node\Name(\Countable::class)
						)
					);
				},
				'isInstanceOf' => function (Scope $scope, Arg $expr, Arg $class): ?\PhpParser\Node\Expr {
					$classType = $scope->getType($class->value);
					if (!$classType instanceof ConstantStringType) {
						return null;
					}

					return new \PhpParser\Node\Expr\Instanceof_(
						$expr->value,
						new \PhpParser\Node\Name($classType->getValue())
					);
				},
				'notInstanceOf' => function (Scope $scope, Arg $expr, Arg $class): ?\PhpParser\Node\Expr {
					$classType = $scope->getType($class->value);
					if (!$classType instanceof ConstantStringType) {
						return null;
					}

					return new \PhpParser\Node\Expr\BooleanNot(
						new \PhpParser\Node\Expr\Instanceof_(
							$expr->value,
							new \PhpParser\Node\Name($classType->getValue())
						)
					);
				},
				'true' => function (Scope $scope, Arg $expr): ?\PhpParser\Node\Expr {
					return new \PhpParser\Node\Expr\BinaryOp\Identical(
						$expr->value,
						new \PhpParser\Node\Expr\ConstFetch(new \PhpParser\Node\Name('true'))
					);
				},
				'false' => function (Scope $scope, Arg $expr): ?\PhpParser\Node\Expr {
					return new \PhpParser\Node\Expr\BinaryOp\Identical(
						$expr->value,
						new \PhpParser\Node\Expr\ConstFetch(new \PhpParser\Node\Name('false'))
					);
				},
				'null' => function (Scope $scope, Arg $expr): ?\PhpParser\Node\Expr {
					return new \PhpParser\Node\Expr\BinaryOp\Identical(
						$expr->value,
						new \PhpParser\Node\Expr\ConstFetch(new \PhpParser\Node\Name('null'))
					);
				},
				'notNull' => function (Scope $scope, Arg $expr): ?\PhpParser\Node\Expr {
					return new \PhpParser\Node\Expr\BinaryOp\NotIdentical(
						$expr->value,
						new \PhpParser\Node\Expr\ConstFetch(new \PhpParser\Node\Name('null'))
					);
				},
				'same' => function (Scope $scope, Arg $value1, Arg $value2): ?\PhpParser\Node\Expr {
					return new \PhpParser\Node\Expr\BinaryOp\Identical(
						$value1->value,
						$value2->value
					);
				},
				'notSame' => function (Scope $scope, Arg $value1, Arg $value2): ?\PhpParser\Node\Expr {
					return new \PhpParser\Node\Expr\BinaryOp\NotIdentical(
						$value1->value,
						$value2->value
					);
				},
			];
		}

		return self::$resolvers;
	}

	private function handleAllNot(
		string $methodName,
		StaticCall $node,
		Scope $scope
	): SpecifiedTypes
	{
		if ($methodName === 'allNotNull') {
			$expr = $node->args[0]->value;
			$currentType = $scope->getType($expr);
			return $this->arrayOrIterable(
				$scope,
				$expr,
				TypeCombinator::removeNull($currentType->getIterableValueType())
			);
		} elseif ($methodName === 'allNotInstanceOf') {
			$classType = $scope->getType($node->args[1]->value);
			if (!$classType instanceof ConstantStringType) {
				return new SpecifiedTypes([], []);
			}

			$expr = $node->args[0]->value;
			$currentType = $scope->getType($expr);
			return $this->arrayOrIterable(
				$scope,
				$expr,
				TypeCombinator::remove(
					$currentType->getIterableValueType(),
					new ObjectType($classType->getValue())
				)
			);
		} elseif ($methodName === 'allNotSame') {
			$expr = $node->args[0]->value;
			$currentType = $scope->getType($expr);
			return $this->arrayOrIterable(
				$scope,
				$expr,
				TypeCombinator::remove(
					$currentType->getIterableValueType(),
					$scope->getType($node->args[1]->value)
				)
			);
		}

		throw new \PHPStan\ShouldNotHappenException();
	}

	private function arrayOrIterable(
		Scope $scope,
		\PhpParser\Node\Expr $expr,
		Type $type
	): SpecifiedTypes
	{
		$currentType = $scope->getType($expr);
		if ((new ArrayType(new MixedType(), new MixedType()))->isSuperTypeOf($currentType)->yes()) {
			$specifiedType = new ArrayType($currentType->getIterableKeyType(), $type);
		} elseif ((new IterableType(new MixedType(), new MixedType()))->isSuperTypeOf($currentType)->yes()) {
			$specifiedType = new IterableType($currentType->getIterableKeyType(), $type);
		} else {
			return new SpecifiedTypes([], []);
		}

		return $this->typeSpecifier->create(
			$expr,
			$specifiedType,
			TypeSpecifierContext::createTruthy()
		);
	}

}
