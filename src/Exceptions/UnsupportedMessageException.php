<?php

declare(strict_types = 1);

namespace SineMacula\Aws\Sns\Exceptions;

/**
 * Unsupported message exception.
 *
 * This exception is thrown when an unsupported SNS message is supplied.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 */
final class UnsupportedMessageException extends \InvalidArgumentException {}
