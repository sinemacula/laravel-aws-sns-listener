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

This package provides a set of Laravel events that can be used to handle AWS SNS notifications in your application.
Below is an overview of the available events, their corresponding message types, and the methods you can use to access
the data they provide.

### Available Events

- **`NotificationReceived`**  
  This is the base event for general SNS notifications. It can be extended for specific types of notifications.

- **`CloudWatchNotificationReceived`**  
  This event is triggered when a CloudWatch alarm changes state. It extends `NotificationReceived`.

- **`S3NotificationReceived`**  
  This event is triggered when an S3 bucket sends a notification, such as when an object is created or deleted. It
  extends `NotificationReceived`.

- **`SesNotificationReceived`**  
  This event is triggered when SES (Simple Email Service) sends a notification, such as a bounce or complaint. It
  extends `NotificationReceived`.

- **`SubscriptionConfirmed`**  
  This event is triggered when a subscription to an SNS topic is confirmed.

### Handling Events

Each event provides a `Message` instance that implements the relevant interface for the notification type. These
interfaces allow you to access the specific data provided by the notification.

#### Base Message

The base `Message` provides the following methods, common to all notification types:

```php
public function getBaseMessage(): BaseMessage; // Aws\Sns\Message
public function getId(): string;
public function getType(): string;
public function getTopic(): string;
public function getTimestamp(): Carbon;
public function getMessage(): stdClass;
public function getSignatureVersion(): string;
public function getSignature(): string;
public function getSigningCertificateUrl(): string;
public function getAttributes(): ?array;
```

#### Subscription Confirmation

When listening for the `SubscriptionConfirmed` event, the message implements the `SubscriptionConfirmationInterface`. In
addition to the base methods, it also provides:

```php
public function getSubscribeUrl(): string;
```

This method returns the URL to confirm the subscription.

#### General Notification

For general notifications (handled by `NotificationReceived`), the message implements the `NotificationInterface`. In
addition to the base methods, it also provides:

```php
public function getUnsubscribeUrl(): ?string;
```

This method returns the URL to unsubscribe from the topic, if available.

#### CloudWatch Notification

The `CloudWatchNotificationReceived` event's message implements the `CloudWatchNotificationInterface`, providing the
following methods:

```php
public function getAlarmName(): string;
public function getAlarmDescription(): ?string;
public function getAwsAccountId(): string;
public function getNewStateValue(): string;
public function getNewStateReason(): string;
public function getOldStateValue(): string;
public function getStateChangeTime(): Carbon;
public function getRegion(): string;
```

These methods allow you to access specific details about the CloudWatch alarm that triggered the notification.

#### S3 Notification

The `S3NotificationReceived` event's message implements the `S3NotificationInterface`, providing the following method:

```php
public function getRecords(): array;
```

This method returns an array of records, each representing an S3 event (e.g., object creation or deletion) that
triggered the notification.

#### SES Notification

The `SesNotificationReceived` event's message implements the `SesNotificationInterface`, providing the following
methods:

```php
public function getNotificationType(): string;
public function getDelivery(): ?Delivery;
public function getBounce(): ?Bounce;
public function getComplaint(): ?Complaint;
public function getMail(): Mail;
```

These methods allow you to access specific details about the SES notification, such as the type of notification (e.g.,
delivery, bounce, or complaint) and additional details specific to that notification type.

## Contributing

Contributions are welcome and will be fully credited. We accept contributions via pull requests on GitHub.

## Security

If you discover any security related issues, please email instead of using the issue tracker.

## License

The Laravel AWS SNS Listener repository is open-sourced software licensed under
the [Apache License, Version 2.0](https://www.apache.org/licenses/LICENSE-2.0).
