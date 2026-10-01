<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\App;

use PHPUnit\Framework\TestCase;

class FakeController {
    public function hello() {
        echo "Hello World";
    }
    public function detail(string $id, string $name) {
        echo "ID: $id, Name: $name";
    }
}

class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        parent::setUp();
        $this->router = new Router();
    }

    public function testRouterSuccess()
    {
        $this->router->get('/home', [FakeController::class, 'hello']);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/home';

        ob_start();
        $this->router->run();
        $output = ob_get_clean();

        self::assertEquals("Hello World", $output);
    }

    public function testRouterWithParameters()
    {
        $this->router->get('/products/{id}/{name}', [FakeController::class, 'detail']);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/products/123/sepatu';

        ob_start();
        $this->router->run();
        $output = ob_get_clean();

        self::assertEquals("ID: 123, Name: sepatu", $output);
    }

    public function testRouter404NotFound()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/halaman-ngawur';

        ob_start();
        $this->router->run();
        $output = ob_get_clean();

        self::assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Halaman tidak ditemukan (404)']), 
            $output
        );
        
        self::assertEquals(404, http_response_code());
    }
}
