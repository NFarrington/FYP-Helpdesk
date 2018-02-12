<?php

namespace Tests\Unit\Repositories;

use App\Models\Department;
use App\Repositories\DepartmentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The repository.
     *
     * @var DepartmentRepository
     */
    protected $repository;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->repository = $this->app->make(DepartmentRepository::class);
    }

    /**
     * Test the getExternal() method.
     *
     * @covers \App\Repositories\DepartmentRepository::getExternal()
     */
    public function testGetExternal()
    {
        factory(Department::class)->states('external')->create();

        $publishedDepartments = $this->repository->getExternal();

        $this->assertEquals(1, $publishedDepartments->count());
    }
}
