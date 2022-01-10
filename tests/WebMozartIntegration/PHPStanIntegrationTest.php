<?php declare(strict_types = 1);

namespace PHPStan\WebMozartIntegration;

use PHPStan\Testing\LevelsTestCase;

final class PHPStanIntegrationTest extends LevelsTestCase
{

	public function dataTopics(): array
	{
		return [
			['bug-33'],
			['bug-85'],
		];
	}

	public function getDataPath(): string
	{
		return __DIR__ . '/data';
	}

	public function getPhpStanExecutablePath(): string
	{
		return __DIR__ . '/../../vendor/phpstan/phpstan/phpstan';
	}

	public function getPhpStanConfigPath(): string
	{
		return __DIR__ . '/phpstan.neon';
	}

}
