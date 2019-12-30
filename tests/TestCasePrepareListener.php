<?php

use Illuminate\Support\Facades\Facade;

class TestCasePrepareListener extends \PHPUnit\Framework\BaseTestListener
{
    public function __destruct()
    {
        if (TestCasePrepare::$migrateRollbackWhenTearDown) {
            echo "Prepare Reset Migrate......" . PHP_EOL;
            static::callArtisan('migrate:reset');
            echo "Reset Migrate Done" . PHP_EOL;
        }
    }

    public function __construct()
    {
        echo "Prepare Migrate Database......" . PHP_EOL;
        static::callArtisan('migrate');
        echo "Migrate Database Done" . PHP_EOL;
    }

    public static function callArtisan($command)
    {
        putenv('APP_ENV=testing');
        Facade::clearResolvedInstances();
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app['Illuminate\Contracts\Console\Kernel']->call($command);
    }

}