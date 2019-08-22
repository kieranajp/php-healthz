<?php
namespace Gentux\Healthz;

/**
 * Manages a set of results from running a stack of health checks
 *
 * @package Gentux\Healthz
 */
class ResultStack
{

    use Stack {
        Stack::push as stackPush;
    }

    public function __construct(array $results = [])
    {
        $this->items = $results;
    }

    public function push(HealthResult $result)
    {
        return $this->stackPush($result);
    }

    /**
     * Determine if any results in the stack have failed
     *
     * @return bool
     */
    public function hasFailures()
    {
        $hasFailure = false;
        foreach ($this->all() as $result) {
            if ($result->failed()) {
                $hasFailure = true;
                break;
            }
        }

        return $hasFailure;
    }

    /**
     * Determine if any results in the stack have warnings
     *
     * @return bool
     */
    public function hasWarnings()
    {
        $hasWarning = false;
        foreach ($this->all() as $result) {
            if ($result->warned()) {
                $hasWarning = true;
                break;
            }
        }

        return $hasWarning;
    }
}
