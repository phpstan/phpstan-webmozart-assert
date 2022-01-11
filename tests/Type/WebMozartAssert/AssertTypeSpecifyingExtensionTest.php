<?php declare(strict_types = 1);

namespace PHPStan\Type\WebMozartAssert;

use PHPStan\Testing\TypeInferenceTestCase;

class AssertTypeSpecifyingExtensionTest extends TypeInferenceTestCase
{

	/**
	 * @return iterable<mixed>
	 */
	public function dataFileAsserts(): iterable
	{
		yield from $this->gatherAssertTypes(__DIR__ . '/data/array.php');
		yield from $this->gatherAssertTypes(__DIR__ . '/data/collection.php');
		yield from $this->gatherAssertTypes(__DIR__ . '/data/comparison.php');
		yield from $this->gatherAssertTypes(__DIR__ . '/data/object.php');
		yield from $this->gatherAssertTypes(__DIR__ . '/data/string.php');
		yield from $this->gatherAssertTypes(__DIR__ . '/data/type.php');
	}

	/**
	 * @dataProvider dataFileAsserts
	 * @param mixed ...$args
	 */
	public function testFileAsserts(
		string $assertType,
		string $file,
		...$args
	): void
	{
		$this->assertFileAsserts($assertType, $file, ...$args);
	}

	public static function getAdditionalConfigFiles(): array
	{
		return [__DIR__ . '/../../../extension.neon'];
	}

}
