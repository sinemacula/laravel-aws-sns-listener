<?php

namespace SineMacula\Aws\Sns\Entities\Messages;

use Aws\Sns\Message as BaseMessage;
use Carbon\Carbon;
use SineMacula\Aws\Sns\Entities\Entity;
use stdClass;

/**
 * The base AWS SNS message instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
abstract class Message extends Entity
{
    /**
     * Create a new message instance.
     *
     * @param  \Aws\Sns\Message  $message
     */
    public function __construct(

        /** The SNS message */
        protected BaseMessage $message

    ) {
        parent::__construct([
            ...$message->toArray(),
            'Message' => json_decode($message['Message'], true)
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
    public function getMessage(): stdClass
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
     * @return array|null
     */
    public function getAttributes(): ?array
    {
        return $this->attributes->MessageAttributes ?? null;
    }
}
