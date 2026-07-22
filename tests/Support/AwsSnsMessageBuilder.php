<?php

declare(strict_types = 1);

// phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint -- arbitrary SNS payload data

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
        $defaultData = [
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

        $data = array_replace($defaultData, $overrides);

        $type           = $data['Type'] ?? '';
        $type           = is_string($type) ? $type : '';
        $isSubscription = $type === 'SubscriptionConfirmation' || $type === 'UnsubscribeConfirmation';

        if ($isSubscription) {
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
     *
     * @throws \Tests\Support\TestSupportException
     */
    public static function makeSignedPayload(array $overrides = []): array
    {
        $resourceConfig = [
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'private_key_bits' => 2048,
        ];

        $privateKey = openssl_pkey_new($resourceConfig);

        if ($privateKey === false) {
            throw new TestSupportException('Unable to generate private key.');
        }

        $certificateSigningRequest = openssl_csr_new([], $privateKey, ['digest_alg' => 'sha256']);

        if ($certificateSigningRequest === false) {
            throw new TestSupportException('Unable to generate certificate signing request.');
        }

        $certificateResource = openssl_csr_sign(
            $certificateSigningRequest,
            null,
            $privateKey,
            1,
            ['digest_alg' => 'sha256'],
        );

        if ($certificateResource === false) {
            throw new TestSupportException('Unable to sign certificate.');
        }

        $certificate         = '';
        $certificateExported = openssl_x509_export($certificateResource, $certificate);

        if ($certificateExported === false) {
            throw new TestSupportException('Unable to export certificate.');
        }

        $data                     = self::makeData($overrides);
        $data['SignatureVersion'] = '1';
        $data['Signature']        = '';

        $validator    = new MessageValidator(static fn (string $certificateUrl): string => $certificateUrl);
        $stringToSign = $validator->getStringToSign(new Message($data));

        $rawSignature = '';
        $signed       = openssl_sign($stringToSign, $rawSignature, $privateKey, OPENSSL_ALGO_SHA1);

        if ($signed === false) {
            throw new TestSupportException('Unable to sign message payload.');
        }

        $data['Signature'] = base64_encode($rawSignature);

        return [
            'data'        => $data,
            'certificate' => $certificate,
        ];
    }
}
