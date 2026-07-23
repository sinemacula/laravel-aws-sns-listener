<?php

declare(strict_types = 1);

namespace Tests\Integration;

use Illuminate\Routing\Route;
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

        $routeCountBefore = count($this->appInstance()['router']->getRoutes()->getRoutes());

        $provider = new SnsServiceProvider($this->appInstance());
        $provider->register();

        $routeCountAfter = count($this->appInstance()['router']->getRoutes()->getRoutes());

        self::assertGreaterThan($routeCountBefore, $routeCountAfter);
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
        $routePath = '/hooks/custom-' . uniqid();
        config()->set('aws.sns.route', $routePath);

        $provider = new SnsServiceProvider($this->appInstance());

        $routeCountBefore = count($this->appInstance()['router']->getRoutes()->getRoutes());

        $provider->register();

        $routes          = $this->appInstance()['router']->getRoutes()->getRoutes();
        $routeCountAfter = count($routes);
        $matchingRoutes  = array_values(array_filter(
            $routes,
            static fn (Route $route): bool => $route->uri() === ltrim($routePath, '/'),
        ));

        self::assertSame($routeCountBefore + 1, $routeCountAfter);
        self::assertCount(1, $matchingRoutes);
        self::assertContains('POST', $matchingRoutes[0]->methods());
        self::assertContains(VerifySnsSignature::class, $matchingRoutes[0]->gatherMiddleware());
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

        $routeCountBefore = count($this->appInstance()['router']->getRoutes()->getRoutes());

        $provider->register();

        $routeCountAfter = count($this->appInstance()['router']->getRoutes()->getRoutes());

        self::assertSame($routeCountBefore, $routeCountAfter);
    }

    /**
     * Publishes config when running in console.
     *
     * @return void
     */
    #[Test]
    public function itPublishesConfigWhenRunningInConsole(): void
    {
        $originalPublishes = IlluminateServiceProvider::$publishes;

        try {
            unset(IlluminateServiceProvider::$publishes[SnsServiceProvider::class]);

            $provider = new SnsServiceProvider($this->appInstance());
            $provider->boot();

            self::assertArrayHasKey(SnsServiceProvider::class, IlluminateServiceProvider::$publishes);
        } finally {
            IlluminateServiceProvider::$publishes = $originalPublishes;
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
        $originalPublishes = IlluminateServiceProvider::$publishes;

        try {
            unset(IlluminateServiceProvider::$publishes[SnsServiceProvider::class]);

            $app = new class {
                /**
                 * Determines if app is running in console mode.
                 *
                 * @return bool
                 *
                 * @imperative
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
            IlluminateServiceProvider::$publishes = $originalPublishes;
        }
    }
}
