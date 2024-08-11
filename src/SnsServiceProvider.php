<?php

namespace SineMacula\Aws\Sns;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use SineMacula\Aws\Sns\Http\Controllers\SnsController;
use SineMacula\Aws\Sns\Http\Middleware\VerifySnsSignature;

/**
 * AWS SNS service provider.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class SnsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->offerPublishing();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/aws.php', 'aws'
        );

        $this->registerTopicManager();
        $this->registerWebhookRoute();
    }

    /**
     * Publish any package specific configuration and assets.
     *
     * @return void
     */
    private function offerPublishing(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        if (!function_exists('config_path')) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../config/aws.php' => config_path('aws.php')
        ], 'config');
    }

    /**
     * Register the SNS topic manager.
     *
     * @return void
     */
    private function registerTopicManager(): void
    {
        $this->app->singleton('sns-topic-manager', function (Application $app) {
            return new TopicManager($app['config']->get('aws.sns.topics', []));
        });
    }

    /**
     * Register the SNS webhook route.
     *
     * @return void
     */
    private function registerWebhookRoute(): void
    {
        $config = $this->app['config']->get('aws.sns');

        if (!$config['route']) {
            return;
        }

        $this->app['router']->post($config['route'], [SnsController::class, 'hook'])
            ->name('aws.sns.webhook')
            ->middleware(VerifySnsSignature::class);
    }
}
