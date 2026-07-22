<?php

declare(strict_types = 1);

namespace SineMacula\Aws\Sns\Entities\Messages\Ses;

use SineMacula\Aws\Sns\Entities\Messages\Contracts\SesNotificationInterface;
use SineMacula\Aws\Sns\Entities\Messages\Notification as BaseNotification;

/**
 * AWS SNS SES notification instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 */
final class Notification extends BaseNotification implements SesNotificationInterface
{
    /** @var \SineMacula\Aws\Sns\Entities\Messages\Ses\Delivery|null Delivery value. */
    protected ?Delivery $delivery = null;

    /** @var \SineMacula\Aws\Sns\Entities\Messages\Ses\Bounce|null Bounce value. */
    protected ?Bounce $bounce = null;

    /** @var \SineMacula\Aws\Sns\Entities\Messages\Ses\Complaint|null Complaint value. */
    protected ?Complaint $complaint = null;

    /** @var \SineMacula\Aws\Sns\Entities\Messages\Ses\Mail|null Mail value. */
    protected ?Mail $mail = null;

    /**
     * Return the notification type.
     *
     * @return string
     */
    #[\Override]
    public function getNotificationType(): string
    {
        return $this->attributes->Message->notificationType;
    }

    /**
     * Return the delivery object.
     *
     * @return \SineMacula\Aws\Sns\Entities\Messages\Ses\Delivery|null
     */
    #[\Override]
    public function getDelivery(): ?Delivery
    {
        return $this->delivery ??= $this->attributes->Message->delivery
            ? new Delivery($this->attributes->Message->delivery)
            : null;
    }

    /**
     * Return the bounce object.
     *
     * @return \SineMacula\Aws\Sns\Entities\Messages\Ses\Bounce|null
     */
    #[\Override]
    public function getBounce(): ?Bounce
    {
        return $this->bounce ??= $this->attributes->Message->bounce
            ? new Bounce($this->attributes->Message->bounce)
            : null;
    }

    /**
     * Return the complaint object.
     *
     * @return \SineMacula\Aws\Sns\Entities\Messages\Ses\Complaint|null
     */
    #[\Override]
    public function getComplaint(): ?Complaint
    {
        return $this->complaint ??= $this->attributes->Message->complaint
            ? new Complaint($this->attributes->Message->complaint)
            : null;
    }

    /**
     * Return the mail object.
     *
     * @return \SineMacula\Aws\Sns\Entities\Messages\Ses\Mail
     */
    #[\Override]
    public function getMail(): Mail
    {
        return $this->mail ??= new Mail($this->attributes->Message->mail);
    }
}
