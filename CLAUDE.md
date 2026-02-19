# CLAUDE.md

This is the `phpstan/phpstan-webmozart-assert` extension for [PHPStan](https://phpstan.org/). It provides type-narrowing support for [webmozart/assert](https://github.com/webmozart/assert) assertion methods.

## Project Goal

PHPStan cannot natively understand type narrowing from `Webmozart\Assert\Assert` static method calls. This extension teaches PHPStan to refine types after assertions like `Assert::integer($a)`, `Assert::notNull($a)`, `Assert::isInstanceOf($a, Foo::class)`, etc. After an assertion call, PHPStan understands the variable's type has been narrowed accordingly.

The extension also supports `nullOr*`, `all*`, and `allNullOr*` method prefixes, as well as negative assertions like `allNotNull`, `allNotInstanceOf`, and `allNotSame`.

## Repository Structure

```
src/Type/WebMozartAssert/
  AssertTypeSpecifyingExtension.php  # The single main extension class
stubs/
  Assert.stub                        # PHPStan stub for Assert methods not covered by the library's own PHPDocs
tests/Type/WebMozartAssert/
  AssertTypeSpecifyingExtensionTest.php          # Type inference tests
  AssertTypeSpecifyingExtensionTestBleedingEdge.php  # Type inference tests with bleeding edge config
  ImpossibleCheckTypeMethodCallRuleTest.php      # Impossible check detection tests
  MethodReturnTypeRuleTest.php                   # Return type rule tests
  data/                                          # Test fixture PHP files (type.php, array.php, bug-*.php, etc.)
extension.neon          # PHPStan extension config (registers the service and stub)
phpstan.neon            # PHPStan config for analysing this project itself
phpstan-baseline.neon   # PHPStan baseline for this project
```

## PHP Version Requirement

This extension supports **PHP 7.4+**. All code must be compatible with PHP 7.4. The `composer.json` platform is pinned to PHP 7.4.6.

## Library Version Support

The extension supports **webmozart/assert ^1.11.0 || ^2.0**. CI tests run with both `--prefer-lowest` and highest dependency versions.

## Development Commands

All commands are defined in the `Makefile`:

- **`make check`** — runs all checks (lint, cs, tests, phpstan)
- **`make tests`** — runs PHPUnit tests (`vendor/bin/phpunit`)
- **`make lint`** — runs PHP parallel lint on `src/` and `tests/`
- **`make cs`** — runs PHP_CodeSniffer with the phpstan/build-cs standard
- **`make cs-fix`** — auto-fixes coding standard violations
- **`make cs-install`** — clones and installs the `phpstan/build-cs` coding standard (2.x branch)
- **`make phpstan`** — runs PHPStan at level 8 on `src/` and `tests/`
- **`make phpstan-generate-baseline`** — regenerates the PHPStan baseline

## How It Works

The extension registers `AssertTypeSpecifyingExtension` as a `phpstan.typeSpecifier.staticMethodTypeSpecifyingExtension` in `extension.neon`. This class implements `StaticMethodTypeSpecifyingExtension` and `TypeSpecifierAwareExtension`.

For each supported `Assert::*` method, the extension translates the assertion into an equivalent PHP expression (using php-parser AST nodes) that PHPStan's type specifier already understands. For example, `Assert::integer($a)` is translated to `is_int($a)`, and `Assert::stringNotEmpty($a)` becomes `is_string($a) && $a !== ''`.

The `getExpressionResolvers()` method returns a map of assertion method names to closures that build the equivalent AST expressions.

## Testing Approach

Tests use PHPStan's `TypeInferenceTestCase` and `RuleTestCase` base classes:

- **Type inference tests** (`AssertTypeSpecifyingExtensionTest`): Use `assertType()` calls in fixture files under `tests/Type/WebMozartAssert/data/` to verify PHPStan resolves the correct types after assertions.
- **Rule tests** (`ImpossibleCheckTypeMethodCallRuleTest`): Verify that impossible/always-true assertion checks are correctly detected.
- **Return type tests** (`MethodReturnTypeRuleTest`): Verify return type rules work correctly with the extension.

Test data files are excluded from PHPStan analysis via `phpstan.neon` (`excludePaths: tests/*/data/*`).

## Coding Style

- **Indentation**: Tabs for PHP, XML, and NEON files; spaces for YAML files (see `.editorconfig`)
- **Coding standard**: Uses `phpstan/build-cs` (2.x branch) — PHP_CodeSniffer with PHPStan's custom ruleset
- **Strict types**: All PHP files use `declare(strict_types = 1)` (note the spaces around `=`)
- **Namespace**: `PHPStan\Type\WebMozartAssert`

## CI

GitHub Actions workflow (`.github/workflows/build.yml`) runs on the `2.0.x` branch and pull requests:

- **Lint**: PHP 7.4–8.4
- **Coding Standard**: PHP 8.2 with `phpstan/build-cs`
- **Tests**: PHP 7.4–8.4 matrix with lowest/highest dependencies
- **Static Analysis (PHPStan)**: PHP 7.4–8.4 matrix with lowest/highest dependencies
