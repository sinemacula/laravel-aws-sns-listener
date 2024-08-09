<?php

namespace SineMacula\Aws\Sns\Entities\Messages\Contracts;

use SineMacula\Aws\Sns\Entities\Messages\Ses\Bounce;
use SineMacula\Aws\Sns\Entities\Messages\Ses\Complaint;
use SineMacula\Aws\Sns\Entities\Messages\Ses\Delivery;
use SineMacula\Aws\Sns\Entities\Messages\Ses\Mail;

/**
 * SES notification interface.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
interface SesNotificationInterface extends NotificationInterface
{
    /**
     * Return the notification type.
     *
     * @return string
     */
    public function getNotificationType(): string;

    /**
     * Return the delivery object.
     *
     * @return \SineMacula\Aws\Sns\Entities\Messages\Ses\Delivery|null
     */
    public function getDelivery(): ?Delivery;

    /**
     * Return the bounce object.
     *
     * @return \SineMacula\Aws\Sns\Entities\Messages\Ses\Bounce|null
     */
    public function getBounce(): ?Bounce;

    /**
     * Return the complaint object.
     *
     * @return \SineMacula\Aws\Sns\Entities\Messages\Ses\Complaint|null
     */
    public function getComplaint(): ?Complaint;

    /**
     * Return the mail object.
     *
     * @return \SineMacula\Aws\Sns\Entities\Messages\Ses\Mail
     */
    public function getMail(): Mail;
}
