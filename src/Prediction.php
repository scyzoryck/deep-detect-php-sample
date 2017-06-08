<?php
declare(strict_types = 1);
namespace Scyzoryck\DeepDetectSample;

class Prediction
{

    /**
     * @var string
     */
    private $class;

    /**
     * @var float
     */
    private $prob;

    public function __construct(string $class, float $prob)
    {
        $this->class = $class;
        $this->prob = $prob;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return float
     */
    public function getProbability(): float
    {
        return $this->prob;
    }
}