<?php
namespace GenTux\Healthz\Bundles\General;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GenTux\Healthz\HealthCheck;
use GuzzleHttp\Exception\RequestException;

class HttpHealthCheckTest extends \TestCase
{

    /** @var Request | Mockery\Mock */
    protected $request;

    /** @var Client | Mockery\Mock */
    protected $guzzle;

    /** @var HttpHealthCheck */
    protected $http;

    public function setUp()
    {
        parent::setUp();
        $this->request = Mockery::mock(Request::class);
        $this->guzzle = Mockery::mock(Client::class);
        $this->http = new HttpHealthCheck($this->request, 200, [], $this->guzzle);
    }

    /** @test */
    public function instance_of_health_check()
    {
        $this->assertInstanceOf(HealthCheck::class, $this->http);
    }

    /** @test */
    public function get_request_to_be_made()
    {
        $result = $this->http->request();
        $this->assertSame($this->request, $result);

        $newRequest = Mockery::mock(Request::class);
        $this->http->setRequest($newRequest);
        $this->assertSame($newRequest, $this->http->request());
    }

    /** @test */
    public function gets_expected_status_code_for_request()
    {
        $result = $this->http->expectedStatusCode();
        $this->assertSame(200, $result);

        $this->http->setExpectedStatusCode(404);
        $result = $this->http->expectedStatusCode();
        $this->assertSame(404, $result);
    }

    /** @test */
    public function gets_guzzle_options()
    {
        $result = $this->http->guzzleOptions();
        $this->assertSame([], $result);

        $this->http->setGuzzleOptions(['foo' => 'bar']);
        $result = $this->http->guzzleOptions();
        $this->assertSame(['foo' => 'bar'], $result);
    }

    /** @test */
    public function run_sends_the_request()
    {
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('getStatusCode')->andReturn(200);

        $this->guzzle->shouldReceive('send')->with($this->request, [])->once()->andReturn($response);

        $this->http->run();
    }

    /**
     * @test
     * @expectedException \GenTux\Healthz\Exceptions\HealthFailureException
     */
    public function run_throws_an_exception_if_the_expected_response_code_doesnt_match()
    {
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('getStatusCode')->andReturn(201);

        $this->guzzle->shouldReceive('send')->andReturn($response);

        $this->http->run();
    }

    /** @test */
    public function run_catches_guzzle_exceptions_to_compare_status_code()
    {
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('getStatusCode')->andReturn(404);

        $e = Mockery::mock(RequestException::class);
        $e->shouldReceive('getResponse')->andReturn($response);

        $this->guzzle->shouldReceive('send')->andThrow($e);

        $this->http->setExpectedStatusCode(404);
        $this->http->run(); // no exceptions, should pass
    }
}
