<?php

namespace SineMacula\Aws\Sns\Entities\Messages;

use Aws\Sns\Message as BaseMessage;
use Carbon\Carbon;
use SineMacula\Aws\Sns\Entities\Entity;

/**
 * The base AWS SNS message instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 */
abstract class Message extends Entity
{
    /**
     * Create a new message instance.
     *
     * @param  \Aws\Sns\Message  $message
     */
    public function __construct(

        /** @var \Aws\Sns\Message The SNS message. */
        protected BaseMessage $message,

    ) {
        $raw_message = $message['Message'];
        $raw_message = is_string($raw_message) ? $raw_message : '';

        $decoded_message = json_decode($raw_message, true);
        $decoded_message = is_array($decoded_message)
            ? $decoded_message
            : ['raw' => $raw_message];

        parent::__construct([
            ...$message->toArray(),
            'Message' => $decoded_message,
        ]);
    }

    /**
     * Return the base message instance.
     *
     * @return \Aws\Sns\Message
     */
    public function getBaseMessage(): BaseMessage
    {
        return $this->message;
    }

    /**
     * Return the message identifier.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->attributes->MessageId;
    }

    /**
     * Return the message type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->attributes->Type;
    }

    /**
     * Return the subscription topic.
     *
     * @return string
     */
    public function getTopic(): string
    {
        return $this->attributes->TopicArn;
    }

    /**
     * Return the message timestamp.
     *
     * @return \Carbon\Carbon
     */
    public function getTimestamp(): Carbon
    {
        return Carbon::parse($this->attributes->Timestamp);
    }

    /**
     * Return the message.
     *
     * @return \stdClass
     */
    public function getMessage(): \stdClass
    {
        return $this->attributes->Message;
    }

    /**
     * Return the signature version.
     *
     * @return string
     */
    public function getSignatureVersion(): string
    {
        return $this->attributes->SignatureVersion;
    }

    /**
     * Return the signature.
     *
     * @return string
     */
    public function getSignature(): string
    {
        return $this->attributes->Signature;
    }

    /**
     * Return the signing certificate url.
     *
     * @return string
     */
    public function getSigningCertificateUrl(): string
    {
        return $this->attributes->SigningCertURL;
    }

    /**
     * Return the message attributes.
     *
     * @return array<string, mixed>|null
     */
    public function getAttributes(): ?array
    {
        $message_attributes = $this->attributes->MessageAttributes ?? null;

        if (!is_array($message_attributes) && !$message_attributes instanceof \stdClass) {
            return null;
        }

        return (array) $message_attributes;
    }
}
