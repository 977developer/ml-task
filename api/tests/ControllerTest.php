<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;
use App\Controller;
use App\Core\Request;
use App\Models\Subscribers;
use Faker\Factory;

class ControllerTest extends TestCase
{
    private $controller;

    protected function setUp(): void
    {
        $dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
        $dotenv->load();
        $this->controller = new Controller();
    }
    
    /**
     * Test list subscribers
     */
    public function testListSubscribers()
    {
        $request = new Request();
        $request->set('page', 1);

        $result = $this->controller->handleListSubscribers($request);

        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('count', $result);
        $this->assertArrayHasKey('currentPage', $result);
        $this->assertArrayHasKey('totalPages', $result);
        $this->assertArrayHasKey('entriesPerPage', $result);
        $this->assertEquals(10, count($result['data']));
        $this->assertArrayHasKey('id', $result['data'][0]);
        $this->assertArrayHasKey('email', $result['data'][0]);
        $this->assertArrayHasKey('firstName', $result['data'][0]);
        $this->assertArrayHasKey('lastName', $result['data'][0]);
        $this->assertArrayHasKey('status', $result['data'][0]);
    }

    /**
     * Test get a single subscriber
     */
    public function testGetSubscriber()
    {
        $faker = Factory::create();

        $fakeData = [
            'firstName' => $faker->firstname,
            'lastName' => $faker->lastname,
            'email' => $faker->unique()->safeEmail,
            'status' => rand(0, 1),
        ];

        $request = new Request();
        $request->set('email', $fakeData['email']);

        Subscribers::insertDB($fakeData, false);

        $result = $this->controller->handleGetSubscriber($request);

        $this->assertSame($result['email'], $fakeData['email']);
        $this->assertSame($result['firstName'], $fakeData['firstName']);
        $this->assertSame($result['lastName'], $fakeData['lastName']);
        $this->assertSame(intval($result['status']), $fakeData['status']);
    }

    /**
     * Test failed getting a single subscriber
     */
    public function testFailedGetSubscriber()
    {
        $faker = Factory::create();

        $request = new Request();
        $request->set('email', 'i do not exist');

        $result = $this->controller->handleGetSubscriber($request);

        $this->assertSame('Subscriber not found', $result);
    }

    /**
     * Test adding a new subscriber
     */
    public function testPostAddSubscriber()
    {
        $faker = Factory::create();

        $fakeData = [
            'firstName' => $faker->firstname,
            'lastName' => $faker->lastname,
            'email' => $faker->unique()->safeEmail,
            'status' => rand(0, 1),
        ];

        $request = new Request();
        $request->set('firstName', $fakeData['firstName']);
        $request->set('lastName', $fakeData['lastName']);
        $request->set('email', $fakeData['email']);
        $request->set('status', $fakeData['status']);

        $result = $this->controller->handlePostSubscribers($request);

        $request = new Request();
        $request->set('email', $fakeData['email']);
        $result = $this->controller->handleGetSubscriber($request);

        $this->assertSame($result['email'], $fakeData['email']);
        $this->assertSame($result['firstName'], $fakeData['firstName']);
        $this->assertSame($result['lastName'], $fakeData['lastName']);
        $this->assertSame(intval($result['status']), $fakeData['status']);
    }
}
