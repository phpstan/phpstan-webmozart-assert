<?php declare(strict_types=1);

namespace Bug17;

use DateTimeInterface;
use Webmozart\Assert\Assert;

(function () {
	Assert::implementsInterface(\DateTime::class, DateTimeInterface::class);
	Assert::implementsInterface(\DateTimeZone::class, DateTimeInterface::class);
})();
