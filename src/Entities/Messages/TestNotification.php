<?php

declare(strict_types = 1);

namespace SineMacula\Aws\Sns\Entities\Messages;

use SineMacula\Aws\Sns\Entities\Messages\Contracts\NotificationInterface;

/**
 * AWS SNS test notification instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 */
final class TestNotification extends Notification implements NotificationInterface {}
