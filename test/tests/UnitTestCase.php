<?php

abstract class UnitTestCase extends Laravel\Lumen\Testing\TestCase
{
    use UnitTestCasePrepare;
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

}
