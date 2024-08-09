<?php

namespace SineMacula\Aws\Sns\Entities\Messages\CloudWatch;

use SineMacula\Aws\Sns\Entities\Entity;

/**
 * AWS Cloud Watch trigger instance.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2024 Sine Macula Limited.
 */
class Trigger extends Entity
{
    /**
     * Return the metric name.
     *
     * @return string
     */
    public function getMetricName(): string
    {
        return $this->attributes->MetricName;
    }

    /**
     * Return the namespace.
     *
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->attributes->MetricName;
    }

    /**
     * Return the statistic type.
     *
     * @return string
     */
    public function getStatisticType(): string
    {
        return $this->attributes->StatisticType;
    }

    /**
     * Return the statistic.
     *
     * @return string
     */
    public function getStatistic(): string
    {
        return $this->attributes->Statistic;
    }

    /**
     * Return the unit.
     *
     * @return string|null
     */
    public function getUnit(): ?string
    {
        return $this->attributes->Unit ?? null;
    }

    /**
     * Return the dimensions.
     *
     * @return array|null
     */
    public function getDimensions(): ?array
    {
        return isset($this->attributes->Dimensions) ? (array) $this->attributes->Dimensions : null;
    }

    /**
     * Return the period.
     *
     * @return int
     */
    public function getPeriod(): int
    {
        return (int) $this->attributes->Period;
    }

    /**
     * Return the evaluation periods.
     *
     * @return int
     */
    public function getEvaluationPeriods(): int
    {
        return (int) $this->attributes->EvaluationPeriods;
    }

    /**
     * Return the comparison operator.
     *
     * @return string
     */
    public function getComparisonOperator(): string
    {
        return $this->attributes->ComparisonOperator;
    }

    /**
     * Return the threshold.
     *
     * @return float
     */
    public function getThreshold(): float
    {
        return $this->attributes->Threshold;
    }

    /**
     * Return the treat missing data.
     *
     * @return string|null
     */
    public function getTreatMissingData(): ?string
    {
        return $this->attributes->TreatMissingData ?? null;
    }

    /**
     * Return the evaluate low sample count percentile.
     *
     * @return string|null
     */
    public function getEvaluateLowSampleCountPercentile(): ?string
    {
        return $this->attributes->EvaluateLowSampleCountPercentile ?? null;
    }

    /**
     * Return the extended statistic.
     *
     * @return string|null
     */
    public function getExtendedStatistic(): ?string
    {
        return $this->attributes->ExtendedStatistic ?? null;
    }
}
