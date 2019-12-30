<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * Lumen 5.5 DatabaseTransaction中已经有以下代码
 * 当前版本的Lumen DatabaseTransaction只支持单数据库
 * Trait MultipleDatabaseTransactions
 */
trait TestCasePrepare
{
    /**
     * determine if executing migrate:rollback after tested
     *
     * @var bool
     */
    public static $migrateRollbackWhenTearDown = true;

    /**
     * The database connections that should rollback
     *
     * @var array
     */
    protected $connectionsToTransact = ['mysql'];

    /**
     * Handle database transactions on the specified connections.
     *
     * @return void
     */
    public function beginDatabaseTransaction()
    {
        $database = $this->app->make('db');
        foreach ($this->connectionsToTransact() as $name) {
            $database->connection($name)->beginTransaction();
        }
        $this->beforeApplicationDestroyed(function () use ($database) {
            foreach ($this->connectionsToTransact() as $name) {
                $connection = $database->connection($name);

                $connection->rollBack();
                $connection->disconnect();
            }
        });
    }

    /**
     * The database connections that should have transactions.
     *
     * @return array
     */
    protected function connectionsToTransact()
    {
        return property_exists($this, 'connectionsToTransact')
            ? $this->connectionsToTransact : [null];
    }

    public function setUpTraits()
    {
        $uses = array_flip(class_uses_recursive(get_class($this)));

        if (isset($uses[DatabaseMigrations::class])) {
            throw new Error('Initialization of database table structure is up to TestCasePrepare');
        }
        if (isset($uses[DatabaseTransactions::class])) {
            throw new Error('Initialization of database transaction is up to TestCasePrepare');
        }

        $this->beginDatabaseTransaction();
        parent::setUpTraits();
    }
}