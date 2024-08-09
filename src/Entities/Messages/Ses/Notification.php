<?php

namespace SineMacula\Aws\Sns\Entities\Messages\Ses;

use SineMacula\Aws\Sns\Entities\Messages\Contracts\SesNotificationInterface;
use SineMacula\Aws\Sns\Entities\Messages\Notification as BaseNotification;

/**
 * AWS SNS SES notification instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class Notification extends BaseNotification implements SesNotificationInterface
{
    /** @var \SineMacula\Aws\Sns\Entities\Messages\Ses\Delivery|null */
    protected ?Delivery $delivery;

    /** @var \SineMacula\Aws\Sns\Entities\Messages\Ses\Bounce|null */
    protected ?Bounce $bounce;

    /** @var \SineMacula\Aws\Sns\Entities\Messages\Ses\Complaint|null */
    protected ?Complaint $complaint;

    /** @var \SineMacula\Aws\Sns\Entities\Messages\Ses\Mail */
    protected Mail $mail;

    /**
     * Return the notification type.
     *
     * @return string
     */
    public function getNotificationType(): string
    {
        return $this->attributes->Message->notificationType;
    }

    /**
     * Return the delivery object.
     *
     * @return \SineMacula\Aws\Sns\Entities\Messages\Ses\Delivery|null
     */
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
    public function getMail(): Mail
    {
        return $this->mail ??= new Mail($this->attributes->Message->mail);
    }
}
