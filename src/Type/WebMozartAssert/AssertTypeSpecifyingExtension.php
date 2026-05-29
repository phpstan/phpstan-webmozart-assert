<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use ArrayAccess;
use Closure;
use Countable;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Greater;
use PhpParser\Node\Expr\BinaryOp\GreaterOrEqual;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BinaryOp\Smaller;
use PhpParser\Node\Expr\BinaryOp\SmallerOrEqual;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Cast\Int_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\SpecifiedTypes;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Analyser\TypeSpecifierAwareExtension;
use PHPStan\Analyser\TypeSpecifierContext;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantArrayTypeBuilder;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\IterableType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NeverType;
use PHPStan\Type\StaticMethodTypeSpecifyingExtension;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use ReflectionObject;
use Traversable;
use function array_key_exists;
use function array_map;
use function array_reduce;
use function array_shift;
use function count;
use function is_array;
use function lcfirst;
use function substr;

class AssertTypeSpecifyingExtension implements StaticMethodTypeSpecifyingExtension, TypeSpecifierAwareExtension
{

	/** @var Closure[] */
	private array $resolvers;

	private ReflectionProvider $reflectionProvider;

	private TypeSpecifier $typeSpecifier;

	public function __construct(ReflectionProvider $reflectionProvider)
	{
		$this->reflectionProvider = $reflectionProvider;
	}

	public function setTypeSpecifier(TypeSpecifier $typeSpecifier): void
	{
		$this->typeSpecifier = $typeSpecifier;
	}

	public function getClass(): string
	{
		return 'Webmozart\Assert\Assert';
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
				&& count($node->getArgs()) >= $methods[$staticMethodReflection->getName()];
		}

		$trimmedName = self::trimName($staticMethodReflection->getName());
		$resolvers = $this->getExpressionResolvers();

		if (!array_key_exists($trimmedName, $resolvers)) {
			return false;
		}

		$resolver = $resolvers[$trimmedName];
		$resolverReflection = new ReflectionObject(Closure::fromCallable($resolver));

		return count($node->getArgs()) >= count($resolverReflection->getMethod('__invoke')->getParameters()) - 1;
	}

	private static function trimName(string $name): string
	{
		if (substr($name, 0, 9) === 'allNullOr') {
			$name = substr($name, 9);
		} elseif (substr($name, 0, 6) === 'nullOr') {
			$name = substr($name, 6);
		} elseif (substr($name, 0, 3) === 'all') {
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
		if (substr($staticMethodReflection->getName(), 0, 9) === 'allNullOr') {
			return $this->handleAll(
				$staticMethodReflection->getName(),
				$node,
				$scope,
				static fn (Type $type) => TypeCombinator::addNull($type),
			);
		}

		if (substr($staticMethodReflection->getName(), 0, 6) === 'allNot') {
			return $this->handleAllNot(
				$staticMethodReflection->getName(),
				$node,
				$scope,
			);
		}

		if (substr($staticMethodReflection->getName(), 0, 3) === 'all') {
			return $this->handleAll(
				$staticMethodReflection->getName(),
				$node,
				$scope,
			);
		}

		[$expr, $specifyOnly] = self::createExpression($scope, $staticMethodReflection->getName(), $node->getArgs());
		if ($expr === null) {
			return new SpecifiedTypes([], []);
		}

		$specifiedTypes = $this->typeSpecifier->specifyTypesInCondition(
			$scope,
			$expr,
			TypeSpecifierContext::createTruthy(),
		);
		if ($specifyOnly) {
			$specifiedTypes = $specifiedTypes->setSpecifyOnly();
		}

		return $specifiedTypes;
	}

	/**
	 * @param Arg[] $args
	 * @return array{?Expr, bool}
	 */
	private function createExpression(
		Scope $scope,
		string $name,
		array $args
	): array
	{
		$trimmedName = self::trimName($name);
		$resolvers = $this->getExpressionResolvers();
		$resolver = $resolvers[$trimmedName];

		$resolverResult = $resolver($scope, ...$args);
		if (is_array($resolverResult)) {
			[$expr, $specifyOnly] = $resolverResult;
		} else {
			$expr = $resolverResult;
			$specifyOnly = false;
		}

		if ($expr === null) {
			return [null, false];
		}

		if (substr($name, 0, 6) === 'nullOr') {
			$expr = new BooleanOr(
				$expr,
				new Identical(
					$args[0]->value,
					new ConstFetch(new Name('null')),
				),
			);
		}

		return [$expr, $specifyOnly];
	}

	/**
	 * @return array<string, callable(Scope, Arg...): (Expr|array{?Expr, bool}|null)>
	 */
	private function getExpressionResolvers(): array
	{
		if (!isset($this->resolvers)) {
			$this->resolvers = [
				'integer' => static fn (Scope $scope, Arg $value): Expr => new FuncCall(
					new Name('is_int'),
					[$value],
				),
				'positiveInteger' => static fn (Scope $scope, Arg $value): Expr => new BooleanAnd(
					new FuncCall(
						new Name('is_int'),
						[$value],
					),
					new Greater(
						$value->value,
						new LNumber(0),
					),
				),
				'string' => static fn (Scope $scope, Arg $value): Expr => new FuncCall(
					new Name('is_string'),
					[$value],
				),
				'stringNotEmpty' => static fn (Scope $scope, Arg $value): Expr => new BooleanAnd(
					new FuncCall(
						new Name('is_string'),
						[$value],
					),
					new NotIdentical(
						$value->value,
						new String_(''),
					),
				),
				'float' => static fn (Scope $scope, Arg $value): Expr => new FuncCall(
					new Name('is_float'),
					[$value],
				),
				'integerish' => static fn (Scope $scope, Arg $value): Expr => new BooleanAnd(
					new FuncCall(
						new Name('is_numeric'),
						[$value],
					),
					new Equal(
						$value->value,
						new Int_(
							$value->value,
						),
					),
				),
				'numeric' => static fn (Scope $scope, Arg $value): Expr => new FuncCall(
					new Name('is_numeric'),
					[$value],
				),
				'natural' => static fn (Scope $scope, Arg $value): Expr => new BooleanAnd(
					new FuncCall(
						new Name('is_int'),
						[$value],
					),
					new GreaterOrEqual(
						$value->value,
						new LNumber(0),
					),
				),
				'boolean' => static fn (Scope $scope, Arg $value): Expr => new FuncCall(
					new Name('is_bool'),
					[$value],
				),
				'scalar' => static fn (Scope $scope, Arg $value): Expr => new FuncCall(
					new Name('is_scalar'),
					[$value],
				),
				'object' => static fn (Scope $scope, Arg $value): Expr => new FuncCall(
					new Name('is_object'),
					[$value],
				),
				'resource' => static fn (Scope $scope, Arg $value): Expr => new FuncCall(
					new Name('is_resource'),
					[$value],
				),
				'isCallable' => static fn (Scope $scope, Arg $value): Expr => new FuncCall(
					new Name('is_callable'),
					[$value],
				),
				'isArray' => static fn (Scope $scope, Arg $value): Expr => new FuncCall(
					new Name('is_array'),
					[$value],
				),
				'isTraversable' => fn (Scope $scope, Arg $value): Expr => $this->resolvers['isIterable']($scope, $value),
				'isIterable' => static fn (Scope $scope, Arg $expr): Expr => new BooleanOr(
					new FuncCall(
						new Name('is_array'),
						[$expr],
					),
					new Instanceof_(
						$expr->value,
						new Name(Traversable::class),
					),
				),
				'isList' => static fn (Scope $scope, Arg $expr): Expr => new BooleanAnd(
					new FuncCall(
						new Name('is_array'),
						[$expr],
					),
					new Identical(
						$expr->value,
						new FuncCall(
							new Name('array_values'),
							[$expr],
						),
					),
				),
				'isNonEmptyList' => fn (Scope $scope, Arg $expr): Expr => new BooleanAnd(
					$this->resolvers['isList']($scope, $expr),
					new NotIdentical(
						$expr->value,
						new Array_(),
					),
				),
				'isMap' => static fn (Scope $scope, Arg $expr): Expr => new BooleanAnd(
					new FuncCall(
						new Name('is_array'),
						[$expr],
					),
					new Identical(
						new FuncCall(
							new Name('array_filter'),
							[$expr, new Arg(new String_('is_string')), new Arg(new ConstFetch(new Name('ARRAY_FILTER_USE_KEY')))],
						),
						$expr->value,
					),
				),
				'isNonEmptyMap' => fn (Scope $scope, Arg $expr): Expr => new BooleanAnd(
					$this->resolvers['isMap']($scope, $expr),
					new NotIdentical(
						$expr->value,
						new Array_(),
					),
				),
				'isCountable' => static fn (Scope $scope, Arg $expr): Expr => new BooleanOr(
					new FuncCall(
						new Name('is_array'),
						[$expr],
					),
					new Instanceof_(
						$expr->value,
						new Name(Countable::class),
					),
				),
				'isInstanceOf' => static function (Scope $scope, Arg $expr, Arg $class): ?Expr {
					$classType = $scope->getType($class->value);
					$classNames = $classType->getObjectTypeOrClassStringObjectType()->getObjectClassNames();

					if (count($classNames) !== 0) {
						return self::implodeExpr(array_map(static fn (string $className): Expr => new Instanceof_($expr->value, new Name($className)), $classNames), BooleanOr::class);
					}

					return new FuncCall(
						new Name('is_object'),
						[$expr],
					);
				},
				'isInstanceOfAny' => fn (Scope $scope, Arg $expr, Arg $classes): ?Expr => self::buildAnyOfExpr($scope, $expr, $classes, $this->resolvers['isInstanceOf']),
				'notInstanceOf' => static function (Scope $scope, Arg $expr, Arg $class): ?Expr {
					$classType = $scope->getType($class->value);
					$classNames = $classType->getObjectTypeOrClassStringObjectType()->getObjectClassNames();

					if (count($classNames) !== 0) {
						$result = self::implodeExpr(array_map(static fn (string $className): Expr => new Instanceof_($expr->value, new Name($className)), $classNames), BooleanOr::class);

						if ($result !== null) {
							return new BooleanNot($result);
						}
					}

					return null;
				},
				'isAOf' => static function (Scope $scope, Arg $expr, Arg $class): Expr {
					$exprType = $scope->getType($expr->value);
					$allowString = (new StringType())->isSuperTypeOf($exprType)->yes();

					return new FuncCall(
						new Name('is_a'),
						[$expr, $class, new Arg(new ConstFetch(new Name($allowString ? 'true' : 'false')))],
					);
				},
				'isAnyOf' => fn (Scope $scope, Arg $value, Arg $classes): ?Expr => self::buildAnyOfExpr($scope, $value, $classes, $this->resolvers['isAOf']),
				'isNotA' => fn (Scope $scope, Arg $value, Arg $class): Expr => new BooleanNot($this->resolvers['isAOf']($scope, $value, $class)),
				'implementsInterface' => function (Scope $scope, Arg $expr, Arg $class): ?Expr {
					$classType = $scope->getType($class->value)->getClassStringObjectType();
					$classNames = $classType->getObjectClassNames();

					if (count($classNames) !== 1) {
						return null;
					}

					if (!$this->reflectionProvider->hasClass($classNames[0])) {
						return null;
					}

					$classReflection = $this->reflectionProvider->getClass($classNames[0]);
					if (!$classReflection->isInterface()) {
						return new ConstFetch(new Name('false'));
					}

					return $this->resolvers['subclassOf']($scope, $expr, $class);
				},
				'keyExists' => static fn (Scope $scope, Arg $array, Arg $key): Expr => new FuncCall(
					new Name('array_key_exists'),
					[$key, $array],
				),
				'keyNotExists' => fn (Scope $scope, Arg $array, Arg $key): Expr => new BooleanNot($this->resolvers['keyExists']($scope, $array, $key)),
				'validArrayKey' => static fn (Scope $scope, Arg $value): Expr => new BooleanOr(
					new FuncCall(
						new Name('is_int'),
						[$value],
					),
					new FuncCall(
						new Name('is_string'),
						[$value],
					),
				),
				'true' => static fn (Scope $scope, Arg $expr): Expr => new Identical(
					$expr->value,
					new ConstFetch(new Name('true')),
				),
				'false' => static fn (Scope $scope, Arg $expr): Expr => new Identical(
					$expr->value,
					new ConstFetch(new Name('false')),
				),
				'null' => static fn (Scope $scope, Arg $expr): Expr => new Identical(
					$expr->value,
					new ConstFetch(new Name('null')),
				),
				'notFalse' => static fn (Scope $scope, Arg $expr): Expr => new NotIdentical(
					$expr->value,
					new ConstFetch(new Name('false')),
				),
				'notNull' => static fn (Scope $scope, Arg $expr): Expr => new NotIdentical(
					$expr->value,
					new ConstFetch(new Name('null')),
				),
				'eq' => static fn (Scope $scope, Arg $value, Arg $value2): Expr => new Equal(
					$value->value,
					$value2->value,
				),
				'notEq' => fn (Scope $scope, Arg $value, Arg $value2): Expr => new BooleanNot($this->resolvers['eq']($scope, $value, $value2)),
				'same' => static fn (Scope $scope, Arg $value1, Arg $value2): Expr => new Identical(
					$value1->value,
					$value2->value,
				),
				'notSame' => static fn (Scope $scope, Arg $value1, Arg $value2): Expr => new NotIdentical(
					$value1->value,
					$value2->value,
				),
				'greaterThan' => static fn (Scope $scope, Arg $value, Arg $limit): Expr => new Greater(
					$value->value,
					$limit->value,
				),
				'greaterThanEq' => static fn (Scope $scope, Arg $value, Arg $limit): Expr => new GreaterOrEqual(
					$value->value,
					$limit->value,
				),
				'lessThan' => static fn (Scope $scope, Arg $value, Arg $limit): Expr => new Smaller(
					$value->value,
					$limit->value,
				),
				'lessThanEq' => static fn (Scope $scope, Arg $value, Arg $limit): Expr => new SmallerOrEqual(
					$value->value,
					$limit->value,
				),
				'range' => static fn (Scope $scope, Arg $value, Arg $min, Arg $max): Expr => new BooleanAnd(
					new GreaterOrEqual(
						$value->value,
						$min->value,
					),
					new SmallerOrEqual(
						$value->value,
						$max->value,
					),
				),
				'subclassOf' => static fn (Scope $scope, Arg $expr, Arg $class): Expr => new FuncCall(
					new Name('is_subclass_of'),
					[
						new Arg($expr->value),
						$class,
					],
				),
				'classExists' => static fn (Scope $scope, Arg $class): Expr => new FuncCall(
					new Name('class_exists'),
					[$class],
				),
				'interfaceExists' => static fn (Scope $scope, Arg $class): Expr => new FuncCall(
					new Name('interface_exists'),
					[$class],
				),
				'count' => static fn (Scope $scope, Arg $array, Arg $number): Expr => new Identical(
					new FuncCall(
						new Name('count'),
						[$array],
					),
					$number->value,
				),
				'minCount' => static fn (Scope $scope, Arg $array, Arg $min): Expr => new GreaterOrEqual(
					new FuncCall(
						new Name('count'),
						[$array],
					),
					$min->value,
				),
				'maxCount' => static fn (Scope $scope, Arg $array, Arg $max): Expr => new SmallerOrEqual(
					new FuncCall(
						new Name('count'),
						[$array],
					),
					$max->value,
				),
				'countBetween' => static fn (Scope $scope, Arg $array, Arg $min, Arg $max): Expr => new BooleanAnd(
					new GreaterOrEqual(
						new FuncCall(
							new Name('count'),
							[$array],
						),
						$min->value,
					),
					new SmallerOrEqual(
						new FuncCall(
							new Name('count'),
							[$array],
						),
						$max->value,
					),
				),
				'length' => static fn (Scope $scope, Arg $value, Arg $length): Expr => new BooleanAnd(
					new FuncCall(
						new Name('is_string'),
						[$value],
					),
					new Identical(
						new FuncCall(
							new Name('strlen'),
							[$value],
						),
						$length->value,
					),
				),
				'minLength' => static fn (Scope $scope, Arg $value, Arg $min): Expr => new BooleanAnd(
					new FuncCall(
						new Name('is_string'),
						[$value],
					),
					new GreaterOrEqual(
						new FuncCall(
							new Name('strlen'),
							[$value],
						),
						$min->value,
					),
				),
				'maxLength' => static fn (Scope $scope, Arg $value, Arg $max): Expr => new BooleanAnd(
					new FuncCall(
						new Name('is_string'),
						[$value],
					),
					new SmallerOrEqual(
						new FuncCall(
							new Name('strlen'),
							[$value],
						),
						$max->value,
					),
				),
				'lengthBetween' => static fn (Scope $scope, Arg $value, Arg $min, Arg $max): Expr => new BooleanAnd(
					new FuncCall(
						new Name('is_string'),
						[$value],
					),
					new BooleanAnd(
						new GreaterOrEqual(
							new FuncCall(
								new Name('strlen'),
								[$value],
							),
							$min->value,
						),
						new SmallerOrEqual(
							new FuncCall(
								new Name('strlen'),
								[$value],
							),
							$max->value,
						),
					),
				),
				'inArray' => static fn (Scope $scope, Arg $needle, Arg $array): Expr => new FuncCall(
					new Name('in_array'),
					[
						$needle,
						$array,
						new Arg(new ConstFetch(new Name('true'))),
					],
				),
				'oneOf' => fn (Scope $scope, Arg $needle, Arg $array): Expr => $this->resolvers['inArray']($scope, $needle, $array),
				'methodExists' => static fn (Scope $scope, Arg $object, Arg $method): Expr => new FuncCall(
					new Name('method_exists'),
					[$object, $method],
				),
				'propertyExists' => static fn (Scope $scope, Arg $object, Arg $property): Expr => new FuncCall(
					new Name('property_exists'),
					[$object, $property],
				),
				'isArrayAccessible' => static fn (Scope $scope, Arg $expr): Expr => new BooleanOr(
					new FuncCall(
						new Name('is_array'),
						[$expr],
					),
					new Instanceof_(
						$expr->value,
						new Name(ArrayAccess::class),
					),
				),
			];

			foreach (['contains', 'startsWith', 'endsWith'] as $name) {
				$this->resolvers[$name] = static function (Scope $scope, Arg $value, Arg $subString): array {
					$expr = new FuncCall(
						new Name('is_string'),
						[$value],
					);

					if ($scope->getType($subString->value)->isNonEmptyString()->yes()) {
						$expr = new BooleanAnd(
							$expr,
							new NotIdentical(
								$value->value,
								new String_(''),
							),
						);
					}

					return [$expr, true];
				};
			}

			$assertionsResultingAtLeastInNonEmptyString = [
				'startsWithLetter',
				'unicodeLetters',
				'alpha',
				'digits',
				'alnum',
				'lower',
				'upper',
				'uuid',
				'ip',
				'ipv4',
				'ipv6',
				'email',
				'notWhitespaceOnly',
			];
			foreach ($assertionsResultingAtLeastInNonEmptyString as $name) {
				$this->resolvers[$name] = static fn (Scope $scope, Arg $value): array => [
					new BooleanAnd(
						new FuncCall(
							new Name('is_string'),
							[$value],
						),
						new NotIdentical(
							$value->value,
							new String_(''),
						),
					),
					true,
				];
			}
		}

		return $this->resolvers;
	}

	private function handleAllNot(
		string $methodName,
		StaticCall $node,
		Scope $scope
	): SpecifiedTypes
	{
		if ($methodName === 'allNotNull') {
			return $this->allArrayOrIterable(
				$scope,
				$node->getArgs()[0]->value,
				static fn (Type $type): Type => TypeCombinator::removeNull($type),
			);
		}

		if ($methodName === 'allNotInstanceOf') {
			$classType = $scope->getType($node->getArgs()[1]->value);
			$classNameType = $classType->getObjectTypeOrClassStringObjectType();
			$classNames = $classNameType->getObjectClassNames();
			if (count($classNames) !== 1) {
				return new SpecifiedTypes([], []);
			}

			return $this->allArrayOrIterable(
				$scope,
				$node->getArgs()[0]->value,
				static fn (Type $type): Type => TypeCombinator::remove($type, $classNameType),
			);
		}

		if ($methodName === 'allNotSame') {
			$valueType = $scope->getType($node->getArgs()[1]->value);
			return $this->allArrayOrIterable(
				$scope,
				$node->getArgs()[0]->value,
				static fn (Type $type): Type => TypeCombinator::remove($type, $valueType),
			);
		}

		throw new ShouldNotHappenException();
	}

	/**
	 * @param callable(Type): Type|null $typeModifier
	 */
	private function handleAll(
		string $methodName,
		StaticCall $node,
		Scope $scope,
		?callable $typeModifier = null
	): SpecifiedTypes
	{
		$args = $node->getArgs();
		$args[0] = new Arg(new ArrayDimFetch($args[0]->value, new LNumber(0)));
		[$expr, $specifyOnly] = self::createExpression($scope, $methodName, $args);
		if ($expr === null) {
			return new SpecifiedTypes();
		}

		$specifiedTypes = $this->typeSpecifier->specifyTypesInCondition(
			$scope,
			$expr,
			TypeSpecifierContext::createTruthy(),
		);

		$sureNotTypes = $specifiedTypes->getSureNotTypes();
		foreach ($specifiedTypes->getSureTypes() as $exprStr => [$exprNode, $type]) {
			if ($exprNode !== $args[0]->value) {
				continue;
			}

			$type = TypeCombinator::remove($type, $sureNotTypes[$exprStr][1] ?? new NeverType());
			if ($typeModifier !== null) {
				$type = $typeModifier($type);
			}

			$specifiedTypes = $this->allArrayOrIterable(
				$scope,
				$node->getArgs()[0]->value,
				static fn (): Type => $type,
			);
			break;
		}

		if ($specifyOnly) {
			$specifiedTypes = $specifiedTypes->setSpecifyOnly();
		}

		return $specifiedTypes;
	}

	private function allArrayOrIterable(
		Scope $scope,
		Expr $expr,
		Closure $typeCallback,
	): SpecifiedTypes
	{
		$currentType = TypeCombinator::intersect($scope->getType($expr), new IterableType(new MixedType(), new MixedType()));
		$arrayTypes = $currentType->getArrays();
		if (count($arrayTypes) > 0) {
			$newArrayTypes = [];
			foreach ($arrayTypes as $arrayType) {
				$constantArrays = $arrayType->getConstantArrays();
				if (count($constantArrays) === 1) {
					$builder = ConstantArrayTypeBuilder::createEmpty();
					foreach ($constantArrays[0]->getKeyTypes() as $i => $keyType) {
						$valueType = $typeCallback($constantArrays[0]->getValueTypes()[$i]);
						if ($valueType instanceof NeverType) {
							continue 2;
						}
						$builder->setOffsetValueType($keyType, $valueType, $constantArrays[0]->isOptionalKey($i));
					}
					$newArrayTypes[] = $builder->getArray();
				} else {
					$itemType = $typeCallback($arrayType->getItemType());
					if ($itemType instanceof NeverType) {
						continue;
					}
					$newArrayTypes[] = new ArrayType($arrayType->getKeyType(), $itemType);
				}
			}

			$specifiedType = TypeCombinator::union(...$newArrayTypes);
		} elseif ((new IterableType(new MixedType(), new MixedType()))->isSuperTypeOf($currentType)->yes()) {
			$itemType = $typeCallback($currentType->getIterableValueType());
			if ($itemType instanceof NeverType) {
				$specifiedType = $itemType;
			} else {
				$specifiedType = new IterableType($currentType->getIterableKeyType(), $itemType);
			}
		} else {
			return new SpecifiedTypes([], []);
		}

		return $this->typeSpecifier->create(
			$expr,
			$specifiedType,
			TypeSpecifierContext::createTruthy(),
			$scope,
		);
	}

	/**
	 * @param Expr[] $expressions
	 * @param class-string<BinaryOp> $binaryOp
	 */
	private static function implodeExpr(array $expressions, string $binaryOp): ?Expr
	{
		$firstExpression = array_shift($expressions);
		if ($firstExpression === null) {
			return null;
		}

		return array_reduce(
			$expressions,
			static fn (Expr $carry, Expr $item) => new $binaryOp($carry, $item),
			$firstExpression,
		);
	}

	private static function buildAnyOfExpr(Scope $scope, Arg $value, Arg $items, callable $resolver): ?Expr
	{
		if (!$items->value instanceof Array_) {
			return null;
		}

		$resolvers = [];
		foreach ($items->value->items as $key => $item) {
			$resolved = $resolver($scope, $value, new Arg($item->value));
			if ($resolved === null) {
				continue;
			}

			$resolvers[$key] = $resolved;
		}

		return self::implodeExpr($resolvers, BooleanOr::class);
	}

}
