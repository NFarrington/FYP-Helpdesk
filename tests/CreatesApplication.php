<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        // use a dedicated testing database if configured
        if (($testingDb = env('DB_TEST_DATABASE')) !== null) {
            $connection = DB::getDefaultConnection();
            config(["database.connections.$connection.database" => $testingDb]);
        }

        return $app;
    }
}
