<?php

declare(strict_types = 1);

namespace Tests\Support;

use Aws\Sns\Message;
use Aws\Sns\MessageValidator;

/**
 * AWS SNS message builder for deterministic test payloads.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
final class AwsSnsMessageBuilder
{
    /**
     * Build a valid AWS SNS message data array.
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function makeData(array $overrides = []): array
    {
        $default_data = [
            'Message'          => '{"value":"default"}',
            'MessageId'        => '12345678-1234-1234-1234-123456789012',
            'Timestamp'        => '2026-01-01T00:00:00Z',
            'TopicArn'         => 'arn:aws:sns:us-east-1:123456789012:test-topic',
            'Type'             => 'Notification',
            'Signature'        => base64_encode('signature'),
            'SigningCertURL'   => 'https://sns.us-east-1.amazonaws.com/SimpleNotificationService-test.pem',
            'SignatureVersion' => '1',
            'UnsubscribeURL'   => 'https://sns.us-east-1.amazonaws.com/unsubscribe',
        ];

        $data = array_replace($default_data, $overrides);

        $type            = $data['Type'] ?? '';
        $type            = is_string($type) ? $type : '';
        $is_subscription = $type === 'SubscriptionConfirmation' || $type === 'UnsubscribeConfirmation';

        if ($is_subscription) {
            $data['SubscribeURL'] ??= 'https://sns.us-east-1.amazonaws.com/subscribe';
            $data['Token']        ??= 'token-value';
        }

        return $data;
    }

    /**
     * Build an AWS SNS message object.
     *
     * @param  array<string, mixed>  $overrides
     * @return \Aws\Sns\Message
     */
    public static function makeMessage(array $overrides = []): Message
    {
        return new Message(self::makeData($overrides));
    }

    /**
     * Build a signed AWS SNS message payload and certificate pair.
     *
     * @param  array<string, mixed>  $overrides
     * @return array{data: array<string, mixed>, certificate: string}
     */
    public static function makeSignedPayload(array $overrides = []): array
    {
        $resource_config = [
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'private_key_bits' => 2048,
        ];

        $private_key = openssl_pkey_new($resource_config);

        if ($private_key === false) {
            throw new TestSupportException('Unable to generate private key.');
        }

        $certificate_signing_request = openssl_csr_new([], $private_key, ['digest_alg' => 'sha256']);

        if ($certificate_signing_request === false) {
            throw new TestSupportException('Unable to generate certificate signing request.');
        }

        $certificate_resource = openssl_csr_sign(
            $certificate_signing_request,
            null,
            $private_key,
            1,
            ['digest_alg' => 'sha256'],
        );

        if ($certificate_resource === false) {
            throw new TestSupportException('Unable to sign certificate.');
        }

        $certificate          = '';
        $certificate_exported = openssl_x509_export($certificate_resource, $certificate);

        if ($certificate_exported === false) {
            throw new TestSupportException('Unable to export certificate.');
        }

        $data                     = self::makeData($overrides);
        $data['SignatureVersion'] = '1';
        $data['Signature']        = '';

        $validator      = new MessageValidator(static fn (string $certificate_url): string => $certificate_url);
        $string_to_sign = $validator->getStringToSign(new Message($data));

        $raw_signature = '';
        $signed        = openssl_sign($string_to_sign, $raw_signature, $private_key, OPENSSL_ALGO_SHA1);

        if ($signed === false) {
            throw new TestSupportException('Unable to sign message payload.');
        }

        $data['Signature'] = base64_encode($raw_signature);

        return [
            'data'        => $data,
            'certificate' => $certificate,
        ];
    }
}
