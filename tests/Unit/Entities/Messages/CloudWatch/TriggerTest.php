<?php

declare(strict_types = 1);

namespace Tests\Unit\Entities\Messages\CloudWatch;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SineMacula\Aws\Sns\Entities\Messages\CloudWatch\Trigger;
use Tests\TestCase;

/**
 * TriggerTest test case.
 *
 * @author      Ben Carey <bdmc@sinemacula.co.uk>
 * @copyright   2026 Sine Macula Limited.
 *
 * @internal
 */
#[CoversClass(Trigger::class)]
final class TriggerTest extends TestCase
{
    /** @var string AWS namespace used for CloudWatch tests. */
    private const string AWS_NAMESPACE = 'AWS/EC2';

    /**
     * Returns trigger values with optionals.
     *
     * @return void
     */
    #[Test]
    public function itReturnsTriggerValuesWithOptionals(): void
    {
        $trigger = new Trigger([
            'MetricName'                       => 'CPUUtilization',
            'Namespace'                        => self::AWS_NAMESPACE,
            'StatisticType'                    => 'Statistic',
            'Statistic'                        => 'AVERAGE',
            'Unit'                             => 'Percent',
            'Dimensions'                       => [(object) ['name' => 'InstanceId']],
            'Period'                           => 300,
            'EvaluationPeriods'                => 1,
            'ComparisonOperator'               => 'GreaterThanThreshold',
            'Threshold'                        => 75.5,
            'TreatMissingData'                 => 'notBreaching',
            'EvaluateLowSampleCountPercentile' => 'evaluate',
            'ExtendedStatistic'                => 'p90',
        ]);

        self::assertSame('CPUUtilization', $trigger->getMetricName());
        self::assertSame(self::AWS_NAMESPACE, $trigger->getNamespace());
        self::assertSame('Statistic', $trigger->getStatisticType());
        self::assertSame('AVERAGE', $trigger->getStatistic());
        self::assertSame('Percent', $trigger->getUnit());
        self::assertCount(1, $trigger->getDimensions() ?? []);
        self::assertSame(300, $trigger->getPeriod());
        self::assertSame(1, $trigger->getEvaluationPeriods());
        self::assertSame('GreaterThanThreshold', $trigger->getComparisonOperator());
        self::assertSame(75.5, $trigger->getThreshold());
        self::assertSame('notBreaching', $trigger->getTreatMissingData());
        self::assertSame('evaluate', $trigger->getEvaluateLowSampleCountPercentile());
        self::assertSame('p90', $trigger->getExtendedStatistic());
    }

    /**
     * Returns null for optional trigger values when missing.
     *
     * @return void
     */
    #[Test]
    public function itReturnsNullForOptionalTriggerValuesWhenMissing(): void
    {
        $trigger = new Trigger([
            'MetricName'         => 'CPUUtilization',
            'Namespace'          => self::AWS_NAMESPACE,
            'StatisticType'      => 'Statistic',
            'Statistic'          => 'AVERAGE',
            'Period'             => 300,
            'EvaluationPeriods'  => 1,
            'ComparisonOperator' => 'GreaterThanThreshold',
            'Threshold'          => 75.5,
        ]);

        self::assertNull($trigger->getUnit());
        self::assertNull($trigger->getDimensions());
        self::assertNull($trigger->getTreatMissingData());
        self::assertNull($trigger->getEvaluateLowSampleCountPercentile());
        self::assertNull($trigger->getExtendedStatistic());
    }
}
