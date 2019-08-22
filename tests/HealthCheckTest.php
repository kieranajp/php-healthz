<?php
namespace Gentux\Healthz;

/**
 * See mock checks that extend the abstract HealthCheck at the bottom
 */
class HealthCheckTest extends \TestCase
{

    /** @var MockCheck */
    protected $check;

    /** @var MockCheckTitle */
    protected $checkWithTitle;

    public function setUp()
    {
        parent::setUp();
        $this->check = new MockCheck();
        $this->checkWithTitle = new MockCheckTitle();
    }

    /** @test */
    public function title_defaults_to_the_class_name()
    {
        $result = $this->check->title();
        $this->assertSame('MockCheck', $result);

        $result = $this->checkWithTitle->title();
        $this->assertSame('Custom Title', $result);
    }
}

/**
 * ----------------------------------------------------------------------
 * Mock Health Checks that extends base abstract class
 * ----------------------------------------------------------------------
 */

class MockCheck extends HealthCheck
{
    public function run()
    {
        return 'all good';
    }
}

class MockCheckTitle extends HealthCheck
{
    protected $title = 'Custom Title';

    public function run()
    {
        return 'all good';
    }
}
