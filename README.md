# PHPStan webmozart/assert extension

[![Build Status](https://travis-ci.org/phpstan/phpstan-webmozart-assert.svg)](https://travis-ci.org/phpstan/phpstan-webmozart-assert)
[![Latest Stable Version](https://poser.pugx.org/phpstan/phpstan-webmozart-assert/v/stable)](https://packagist.org/packages/phpstan/phpstan-webmozart-assert)
[![License](https://poser.pugx.org/phpstan/phpstan-webmozart-assert/license)](https://packagist.org/packages/phpstan/phpstan-webmozart-assert)

* [PHPStan](https://github.com/phpstan/phpstan)
* [webmozart/assert](https://github.com/webmozart/assert)

This extension specifies types of values passed to:

* `Assert::integer`
* `Assert::string`
* `Assert::float`
* `Assert::numeric`
* `Assert::boolean`
* `Assert::scalar`
* `Assert::object`
* `Assert::resource`
* `Assert::isCallable`
* `Assert::isArray`
* `Assert::isIterable`
* `Assert::isCountable`
* `Assert::isInstanceOf`
* `Assert::notInstanceOf`
* `Assert::subclassOf`
* `Assert::true`
* `Assert::false`
* `Assert::null`
* `Assert::notNull`
* `Assert::same`
* `Assert::notSame`
* `nullOr*` and `all*` variants of the above methods

## Usage

To use this extension, require it in [Composer](https://getcomposer.org/):

```bash
composer require --dev phpstan/phpstan-webmozart-assert
```

And include extension.neon in your project's PHPStan config:

```
includes:
	- vendor/phpstan/phpstan-webmozart-assert/extension.neon
```
