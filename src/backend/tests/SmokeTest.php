<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Kernel;

class SmokeTest extends TestCase
{
    public function testKernelBoots(): void
    {
        $kernel = new Kernel('test', true);

        $kernel->boot();

        $this->assertInstanceOf(Kernel::class, $kernel);
    }
}
