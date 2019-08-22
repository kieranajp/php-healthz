[![Build Status](https://travis-ci.org/generationtux/php-healthz.svg?branch=master)](https://travis-ci.org/generationtux/php-healthz)
[![Test Coverage](https://codeclimate.com/github/generationtux/php-healthz/badges/coverage.svg)](https://codeclimate.com/github/generationtux/php-healthz/coverage)

# PHP Healthz
Health checking for PHP apps.

Get an easy overview of the health of your app! Implement a health check endpoint for load balancers, or your own sanity :)

All credit to [generationtux](https://github.com/generationtux/php-healthz) for the upstream version. This is a fork of that with a lot of stuff removed. Notably:

- No Laravel support (no dependency on Illuminate components!)
- No UI (no dependency on Twig!)
- No defined checks - roll your own only.

- [Setup and usage](#setup)
- [Writing checks](#writing-checks)

----------------------------------------------------------------------------

## Setup

```sh
$ composer require generationtux/healthz
```

**Build an instance of the health check**
```php
<?php
use Gentux\Healthz\Healthz;

$memcached = (new MemcachedHealthCheck())->addServer('127.0.0.1');
$healthz = new Healthz([$memcached]);
```

**Run the checks and review results**
```php
// @var $results Gentux\Healthz\ResultStack
$results = $healthz->run();

if ($results->hasFailures()) {
    // oh no
}

if ($results->hasWarnings()) {
    // hmm
}

foreach ($results->all() as $result) {
    // @var $result Gentux\Healthz\HealthResult
    if ($result->passed() || $result->warned() || $result->failed()) {
        echo "it did one of those things at least";
    }

    echo "{$result->title()}: {$result->status()} ({$result->description()})";
}
```

----------------------------------------------------------------------------

## Writing checks

*Note: Checks may have one of 3 statuses (`success`, `warning`, or `failure`). Any combination of success and warning and the stack as a whole will be considered to be successful.
Any single failure, however, will consider the stack to be failed.*

To create a custom health check, you should extend `Gentux\Healthz\HealthCheck` and implement the one abstract method `run()`.

```php
<?php

use Gentux\Healthz\HealthCheck;

class MyCustomCheck extends HealthCheck {

    /** @var string Optionally set a title, otherwise the class name will be used */
    protected $title = '';

    /** @var string Optionally set a description, just to provide more info on the UI */
    protected $description = '';

    public function run()
    {
        // any exception that is thrown will consider the check unhealthy
    }
}
```

If no exception is thrown, the check will be presumed to have been successful. Otherwise, the exception's message will be used as the `status` of the failed check.
```php
public function run()
{
    throw new Exception('Heres why the check failed.');
}
```

If you would like the check to show a `warning` instead of a full failure, throw an instance of `Gentux\Healthz\Exceptions\HealthWarningException`.
```php
use Gentux\Healthz\Exceptions\HealthWarningException;

public function run()
{
    throw new HealthWarningException("The check didn't fail, but here ye be warned.");
}
```
