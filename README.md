# PHPStan webmozart/assert extension

[![Build](https://github.com/phpstan/phpstan-webmozart-assert/workflows/Build/badge.svg)](https://github.com/phpstan/phpstan-webmozart-assert/actions)
[![Latest Stable Version](https://poser.pugx.org/phpstan/phpstan-webmozart-assert/v/stable)](https://packagist.org/packages/phpstan/phpstan-webmozart-assert)
[![License](https://poser.pugx.org/phpstan/phpstan-webmozart-assert/license)](https://packagist.org/packages/phpstan/phpstan-webmozart-assert)

* [PHPStan](https://phpstan.org/)
* [webmozart/assert](https://github.com/webmozart/assert)

## Description

The main scope of this extension is to help PHPStan to detect the type of object after the `Webmozart\Assert\Assert` validation.

```php
<?php declare(strict_types = 1);

use Webmozart\Assert\Assert;

function demo(?int $a) {
	// ...

	Assert::integer($a);
	// PHPStan is now aware that $a can no longer be `null` at this point

	return ($a === 10);
}
```

## Supported assertions

This extension understands the following `Assert::*` methods and narrows types accordingly.

All assertions also work with the `nullOr*()`, `all*()`, and `allNullOr*()` prefixes (e.g. `Assert::nullOrString()`, `Assert::allInteger()`, `Assert::allNullOrNotEmpty()`).

### Type checks

`integer`, `positiveInteger`, `natural`, `float`, `numeric`, `integerish`, `boolean`, `scalar`, `string`, `stringNotEmpty`, `object`, `resource`, `isCallable`, `isArray`, `isIterable`, `isTraversable`, `isList`, `isNonEmptyList`, `isMap`, `isNonEmptyMap`, `isCountable`, `isArrayAccessible`

### Instance and class checks

`isInstanceOf`, `isInstanceOfAny`, `notInstanceOf`, `isAOf`, `isAnyOf`, `isNotA`, `subclassOf`, `implementsInterface`, `classExists`, `interfaceExists`

### Comparison

`same`, `notSame`, `eq`, `notEq`, `greaterThan`, `greaterThanEq`, `lessThan`, `lessThanEq`, `range`, `true`, `false`, `null`, `notNull`, `notFalse`, `inArray`, `oneOf`

### String assertions

`contains`, `startsWith`, `endsWith`, `startsWithLetter`, `unicodeLetters`, `alpha`, `digits`, `alnum`, `lower`, `upper`, `uuid`, `ip`, `ipv4`, `ipv6`, `email`, `notWhitespaceOnly`, `length`, `minLength`, `maxLength`, `lengthBetween`

### Count assertions

`count`, `minCount`, `maxCount`, `countBetween`

### Object and array

`keyExists`, `keyNotExists`, `validArrayKey`, `methodExists`, `propertyExists`

### Negative `all*` assertions

`allNotNull`, `allNotInstanceOf`, `allNotSame`

## Installation

To use this extension, require it in [Composer](https://getcomposer.org/):

```
composer require --dev phpstan/phpstan-webmozart-assert
```

If you also install [phpstan/extension-installer](https://github.com/phpstan/extension-installer) then you're all set!

<details>
  <summary>Manual installation</summary>

If you don't want to use `phpstan/extension-installer`, include extension.neon in your project's PHPStan config:

```
includes:
    - vendor/phpstan/phpstan-webmozart-assert/extension.neon
```
</details>
