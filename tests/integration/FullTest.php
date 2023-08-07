<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class FullTest extends TestCase
{
    public function testInt(): void
    {
        self::assertSame(1, 1);
    }
}
