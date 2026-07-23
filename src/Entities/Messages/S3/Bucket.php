<?php

declare(strict_types = 1);

namespace SineMacula\Aws\Sns\Entities\Messages\S3;

use SineMacula\Aws\Sns\Entities\Entity;

/**
 * AWS S3 bucket instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 */
final class Bucket extends Entity
{
    /**
     * Return the name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->attributes->name;
    }

    /**
     * Return the ARN.
     *
     * @return string
     */
    public function getArn(): string
    {
        return $this->attributes->arn;
    }
}
