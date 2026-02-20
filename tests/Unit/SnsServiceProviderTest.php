<?php

declare(strict_types = 1);

namespace Tests\Unit;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SineMacula\Aws\Sns\SnsServiceProvider;

/**
 * SnsServiceProviderTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(SnsServiceProvider::class)]
final class SnsServiceProviderTest extends TestCase
{
    /**
     * Returns early when config_path function is not available.
     *
     * @return void
     */
    #[PreserveGlobalState(false)]
    #[RunInSeparateProcess]
    #[Test]
    public function itReturnsEarlyWhenConfigPathFunctionIsNotAvailable(): void
    {
        $function_override = <<<'PHP'
            namespace SineMacula\Aws\Sns;

            function function_exists(string $function): bool
            {
                if (($GLOBALS['__sns_force_missing_config_path'] ?? false) && $function === 'config_path') {
                    return false;
                }

                return \function_exists($function);
            }
            PHP;

        if (!\function_exists('SineMacula\Aws\Sns\function_exists')) {
            eval($function_override);
        }

        $provider_class = '\SineMacula\Aws\Sns\SnsServiceProvider';
        unset(IlluminateServiceProvider::$publishes[$provider_class]);

        $app = new class {
            /**
             * Determines if the application is running in console mode.
             *
             * @return bool
             */
            public function runningInConsole(): bool
            {
                return true;
            }
        };

        try {
            $GLOBALS['__sns_force_missing_config_path'] = true;

            $provider = new $provider_class($app);
            $provider->boot();
        } finally {
            $GLOBALS['__sns_force_missing_config_path'] = false;
        }

        self::assertArrayNotHasKey($provider_class, IlluminateServiceProvider::$publishes);
    }
}
