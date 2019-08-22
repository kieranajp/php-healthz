<?php
namespace Gentux\Healthz;

use Exception;
use Gentux\Healthz\Exceptions\HealthWarningException;

/**
 * Collection of health checks to run.
 *
 * @package Gentux\Healthz
 */
class Healthz
{
    use Stack;

    /**
     * @param HealthCheck[] $healthChecks
     */
    public function __construct($healthChecks = [])
    {
        $this->items = $healthChecks;
    }

    /**
     * Run the health checks in the stack
     *
     * @return ResultStack
     */
    public function run()
    {
        $results = [];

        foreach ($this->all() as $check) {
            $resultCode = HealthResult::RESULT_SUCCESS;

            try {
                $check->run();
            } catch (Exception $e) {
                $check->setStatus($e->getMessage());
                $resultCode = $e instanceof HealthWarningException ?
                    HealthResult::RESULT_WARNING :
                    HealthResult::RESULT_FAILURE;
            }

            $results[] = new HealthResult($resultCode, $check);
        }

        return new ResultStack($results);
    }
}
