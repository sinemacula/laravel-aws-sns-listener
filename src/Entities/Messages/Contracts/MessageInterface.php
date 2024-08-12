<?php

namespace SineMacula\Aws\Sns\Entities\Messages\Contracts;

use Aws\Sns\Message as BaseMessage;
use Carbon\Carbon;
use stdClass;

/**
 * Message interface.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
interface MessageInterface
{
    /**
     * Return the base message instance.
     *
     * @return \Aws\Sns\Message
     */
    public function getBaseMessage(): BaseMessage;

    /**
     * Return the message identifier.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Return the message type.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Return the subscription topic.
     *
     * @return string
     */
    public function getTopic(): string;

    /**
     * Return the message timestamp.
     *
     * @return \Carbon\Carbon
     */
    public function getTimestamp(): Carbon;

    /**
     * Return the message.
     *
     * @return \stdClass
     */
    public function getMessage(): stdClass;

    /**
     * Return the signature version.
     *
     * @return string
     */
    public function getSignatureVersion(): string;

    /**
     * Return the signature.
     *
     * @return string
     */
    public function getSignature(): string;

    /**
     * Return the signing certificate url.
     *
     * @return string
     */
    public function getSigningCertificateUrl(): string;

    /**
     * Return the message attributes.
     *
     * @return array|null
     */
    public function getAttributes(): ?array;
}
