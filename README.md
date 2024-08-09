# Laravel AWS SNS Listener

[![Latest Stable Version](https://img.shields.io/packagist/v/sinemacula/laravel-aws-sns-listener.svg)](https://packagist.org/packages/sinemacula/laravel-aws-sns-listener)
[![Build Status](https://github.com/sinemacula/laravel-aws-sns-listener/actions/workflows/tests.yml/badge.svg?branch=master)](https://github.com/sinemacula/laravel-aws-sns-listener/actions/workflows/tests.yml)
[![StyleCI](https://github.styleci.io/repos/839572387/shield?style=flat&branch=master)](https://github.styleci.io/repos/839572387)
[![Maintainability](https://api.codeclimate.com/v1/badges/1471641388c9d7481777/maintainability)](https://codeclimate.com/github/sinemacula/laravel-aws-sns-listener/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/1471641388c9d7481777/test_coverage)](https://codeclimate.com/github/sinemacula/laravel-aws-sns-listener/test_coverage)
[![Total Downloads](https://img.shields.io/packagist/dt/sinemacula/laravel-aws-sns-listener.svg)](https://packagist.org/packages/sinemacula/laravel-aws-sns-listener)

The Laravel AWS SNS Listener is a comprehensive package designed to handle AWS SNS notifications in Laravel
applications. It provides tools to easily configure SNS subscriptions, process notifications, and trigger Laravel
events, making integration with AWS SNS seamless and efficient.

## Features

- **Subscription Confirmation**: Automatically handles AWS SNS subscription confirmations to ensure secure and expected
  subscriptions.
- **Event Handling**: Triggers native Laravel events when SNS notifications are received, allowing for flexible and
  extensible handling of different notification types.
- **Configurable Routes**: Easily configure the route for receiving SNS notifications, providing flexibility in routing
  and security.
- **Expected Topics**: Maintain a list of expected SNS topics to ensure only legitimate subscriptions are accepted.

## Installation

To install the Laravel AWS SNS Listener, run the following command in your project directory:

```bash
composer require sinemacula/laravel-aws-sns-listener
```

## Configuration

After installation, publish the package configuration to customize it according to your needs:

```bash
php artisan vendor:publish --provider="SineMacula\Aws\Sns\SnsServiceProvider"
```

This command publishes the package configuration file to your application's config directory, allowing you to modify
aspects such as the SNS route and expected topics.

## Usage

Detailed usage instructions will be provided soon. This section will cover how to integrate the listener into your
Laravel application, including setting up the route for SNS notifications, handling subscription confirmations, and
registering event listeners.

## Contributing

Contributions are welcome and will be fully credited. We accept contributions via pull requests on GitHub.

## Security

If you discover any security related issues, please email instead of using the issue tracker.

## License

The Laravel AWS SNS Listener repository is open-sourced software licensed under
the [Apache License, Version 2.0](https://www.apache.org/licenses/LICENSE-2.0).
