<?php

declare(strict_types = 1);

namespace Tests\Integration;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Http\Middleware\VerifySnsSignature;
use SineMacula\Aws\Sns\SnsServiceProvider;
use SineMacula\Aws\Sns\TopicManager;
use Tests\TestCase;

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
     * Boots the service provider.
     *
     * @return void
     */
    #[Test]
    public function itBootsTheServiceProvider(): void
    {
        $provider = new SnsServiceProvider($this->appInstance());

        $provider->boot();

        self::assertInstanceOf(SnsServiceProvider::class, $provider);
    }

    /**
     * Registers services with the register method.
     *
     * @return void
     */
    #[Test]
    public function itRegistersServicesWithTheRegisterMethod(): void
    {
        config()->set('aws.sns.topics', ['topic-a', 'topic-b']);
        config()->set('aws.sns.route', '/hooks/register-' . uniqid());

        $route_count_before = count($this->appInstance()['router']->getRoutes()->getRoutes());

        $provider = new SnsServiceProvider($this->appInstance());
        $provider->register();

        $route_count_after = count($this->appInstance()['router']->getRoutes()->getRoutes());

        self::assertGreaterThan($route_count_before, $route_count_after);
        self::assertInstanceOf(TopicManager::class, $this->appInstance()->make('sns-topic-manager'));
    }

    /**
     * Registers topic manager from configuration.
     *
     * @return void
     */
    #[Test]
    public function itRegistersTopicManagerFromConfiguration(): void
    {
        config()->set('aws.sns.topics', ['topic-a', 'topic-b']);
        config()->set('aws.sns.route', false);

        $provider = new SnsServiceProvider($this->appInstance());
        $provider->register();

        $manager = $this->appInstance()->make('sns-topic-manager');

        self::assertInstanceOf(TopicManager::class, $manager);
        self::assertSame(['topic-a', 'topic-b'], $manager->getTopics());
    }

    /**
     * Registers webhook route when configured.
     *
     * @return void
     */
    #[Test]
    public function itRegistersWebhookRouteWhenConfigured(): void
    {
        $route_path = '/hooks/custom-' . uniqid();
        config()->set('aws.sns.route', $route_path);

        $provider = new SnsServiceProvider($this->appInstance());

        $route_count_before = count($this->appInstance()['router']->getRoutes()->getRoutes());

        $provider->register();

        $routes            = $this->appInstance()['router']->getRoutes()->getRoutes();
        $route_count_after = count($routes);
        $matching_routes   = array_values(array_filter(
            $routes,
            static fn (\Illuminate\Routing\Route $route): bool => $route->uri() === ltrim($route_path, '/'),
        ));

        self::assertSame($route_count_before + 1, $route_count_after);
        self::assertCount(1, $matching_routes);
        self::assertContains('POST', $matching_routes[0]->methods());
        self::assertContains(VerifySnsSignature::class, $matching_routes[0]->gatherMiddleware());
    }

    /**
     * Skips webhook route registration when route is disabled.
     *
     * @return void
     */
    #[Test]
    public function itSkipsWebhookRouteRegistrationWhenRouteIsDisabled(): void
    {
        config()->set('aws.sns.route', false);

        $provider = new SnsServiceProvider($this->appInstance());

        $route_count_before = count($this->appInstance()['router']->getRoutes()->getRoutes());

        $provider->register();

        $route_count_after = count($this->appInstance()['router']->getRoutes()->getRoutes());

        self::assertSame($route_count_before, $route_count_after);
    }

    /**
     * Publishes config when running in console.
     *
     * @return void
     */
    #[Test]
    public function itPublishesConfigWhenRunningInConsole(): void
    {
        $original_publishes = IlluminateServiceProvider::$publishes;

        try {
            unset(IlluminateServiceProvider::$publishes[SnsServiceProvider::class]);

            $provider = new SnsServiceProvider($this->appInstance());
            $provider->boot();

            self::assertArrayHasKey(SnsServiceProvider::class, IlluminateServiceProvider::$publishes);
        } finally {
            IlluminateServiceProvider::$publishes = $original_publishes;
        }
    }

    /**
     * Returns early when not running in console.
     *
     * @return void
     */
    #[Test]
    public function itReturnsEarlyWhenNotRunningInConsole(): void
    {
        $original_publishes = IlluminateServiceProvider::$publishes;

        try {
            unset(IlluminateServiceProvider::$publishes[SnsServiceProvider::class]);

            $app = new class {
                /**
                 * Determines if app is running in console mode.
                 *
                 * @return bool
                 */
                public function runningInConsole(): bool
                {
                    return false;
                }
            };

            $provider = new SnsServiceProvider($app);
            $provider->boot();

            self::assertArrayNotHasKey(SnsServiceProvider::class, IlluminateServiceProvider::$publishes);
        } finally {
            IlluminateServiceProvider::$publishes = $original_publishes;
        }
    }
}
