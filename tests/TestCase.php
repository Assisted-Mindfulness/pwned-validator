<?php

declare(strict_types=1);

namespace AssistedMindfulness\Pwned\Tests;

use AssistedMindfulness\Pwned\PwnedServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getPackageProviders($app): array
    {
        return [
            PwnedServiceProvider::class,
        ];
    }
}
