<?php declare(strict_types = 1);

namespace WebmozartAssertBug68;

use Webmozart\Assert\Assert;
use function explode;

$encryptedValue = 'some value';
$valueParts = explode(':', $encryptedValue);

Assert::count(
	$valueParts,
	2,
	'Encrypted secret parameter was expected to consist of 2 parts separated by a colon'
);
