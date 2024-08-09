<?php

namespace SineMacula\Aws\Sns\Exceptions;

use InvalidArgumentException;

/**
 * Unsupported message exception.
 *
 * This exception is thrown when an unsupported SNS message is supplied.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class UnsupportedMessageException extends InvalidArgumentException { }
