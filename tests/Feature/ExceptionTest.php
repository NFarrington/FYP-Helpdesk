<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExceptionTest extends TestCase
{
    /**
     * Test a request to an unknown URL.
     *
     * @return void
     */
    public function test404PageNotFound()
    {
        $response = $this->get(str_random(32));

        $response->assertStatus(404);
    }
}
