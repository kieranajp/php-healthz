<?php
namespace Gentux\Healthz;

/**
 * DTO representing the result of a health check
 *
 * @package Gentux\Healthz
 */
class HealthResult
{
    const RESULT_SUCCESS = 0;
    const RESULT_WARNING = 1;
    const RESULT_FAILURE = 2;

    /** @var int Should be one of RESULT constants above */
    protected $result;

    /** @var HealthCheck */
    protected $check;

    public function __construct($result, HealthCheck $check)
    {
        $this->result = $result;
        $this->check = $check;
    }

    /**
     * Determine if the result is a failure
     *
     * @return bool
     */
    public function failed()
    {
        return $this->result() === self::RESULT_FAILURE;
    }

    /**
     * Determine if the result is a success
     *
     * @return bool
     */
    public function passed()
    {
        return $this->result() === self::RESULT_SUCCESS;
    }

    /**
     * Determine if the result is a warning
     *
     * @return bool
     */
    public function warned()
    {
        return $this->result() === self::RESULT_WARNING;
    }

    /** Getters: information about the health check */

    public function title()
    {
        return $this->check->title();
    }

    public function description()
    {
        return $this->check->description();
    }

    public function status()
    {
        return $this->check->status();
    }

    public function result()
    {
        return $this->result;
    }
}
