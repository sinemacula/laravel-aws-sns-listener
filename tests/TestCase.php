<?php

declare(strict_types = 1);

namespace Tests;

use Illuminate\Contracts\Foundation\Application;
use Orchestra\Testbench\TestCase as BaseTestCase;
use SineMacula\Aws\Sns\SnsServiceProvider;

/**
 * Package test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * Return package service providers.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return array<int, class-string>
     */
    #[\Override]
    protected function getPackageProviders(mixed $app): array
    {
        return [
            SnsServiceProvider::class,
        ];
    }

    /**
     * Return the application instance.
     *
     * @return \Illuminate\Contracts\Foundation\Application
     */
    protected function appInstance(): Application
    {
        static::assertNotNull($this->app);

        return $this->app;
    }
}
