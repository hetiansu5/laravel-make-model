<?php

use Symfony\Component\Console\Exception\RuntimeException;

class MakeModelCommandTest extends TestCase
{

    private $path;

    public function setUp()
    {
        parent::setUp();
        $this->path = app()->path() . '/Models/User.php';
        if (file_exists($this->path)) {
            unlink($this->path);
        }
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testHandle_MissingParameter_Table()
    {
        $this->expectException(\Exception::class);
        $this->artisan('make:e-model', [
            '-m' => 'User',
        ]);
    }

    public function testHandle_MissingOption_Model()
    {
        $this->artisan('make:e-model', [
            'table' => 'users',
        ]);
        $this->assertSame(true, file_exists($this->path));
    }

    public function testHandle()
    {
        $this->artisan('make:e-model', [
            'table' => 'users',
            '-m' => 'User',
        ]);
        $this->assertSame(true, file_exists($this->path));
    }

}