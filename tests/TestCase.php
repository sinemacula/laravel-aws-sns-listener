<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use SineMacula\Aws\Sns\SnsServiceProvider;

/**
 * Package test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * Return package service providers.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            SnsServiceProvider::class
        ];
    }
}
