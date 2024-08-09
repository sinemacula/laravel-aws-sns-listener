<?php

namespace SineMacula\Aws\Sns\Entities\Messages\S3;

use SineMacula\Aws\Sns\Entities\Entity;

/**
 * AWS S3 bucket instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class Bucket extends Entity
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
