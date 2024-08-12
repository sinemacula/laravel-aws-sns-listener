<?php

namespace SineMacula\Aws\Sns\Entities\Messages;

use SineMacula\Aws\Sns\Entities\Messages\Contracts\NotificationInterface;

/**
 * AWS SNS test notification instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class TestNotification extends Notification implements NotificationInterface
{ }
