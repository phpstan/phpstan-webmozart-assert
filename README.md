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
