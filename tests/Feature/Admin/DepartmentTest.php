<?php

namespace Tests\Feature\Admin;

use App\Models\Department;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test user.
     *
     * @var User
     */
    protected $user;

    /**
     * A basic test department.
     *
     * @var \App\Models\Department
     */
    protected $department;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->user->roles()->attach(Role::admin()->id);
        $this->actingAs($this->user);

        $this->department = factory(Department::class)->create();
    }

    public function testDepartmentIndexPageLoads()
    {
        $response = $this->get(route('admin.departments.index'));

        $response->assertStatus(200);
    }

    public function testDepartmentCreatePageLoads()
    {
        $response = $this->get(route('admin.departments.create'));

        $response->assertStatus(200);
    }

    public function testDepartmentCanBeCreated()
    {
        $department = factory(Department::class)->make();
        $this->get(route('admin.departments.create'));
        $response = $this->post(route('admin.departments.store'), [
            'name' => $department->name,
            'description' => $department->description,
            'internal' => $department->internal,
            'users' => [$this->user->id],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', trans('department.created'));
        $this->assertDatabaseHas('departments', [
            'name' => $department->name,
            'description' => $department->description,
        ]);
    }

    public function testDepartmentShowPageRedirects()
    {
        $response = $this->get(route('admin.departments.show', $this->department));

        $response->assertRedirect(route('admin.departments.edit', $this->department));
    }

    public function testDepartmentEditPageLoads()
    {
        $response = $this->get(route('admin.departments.edit', $this->department));

        $response->assertStatus(200);
        $response->assertSee($this->department->name);
    }

    public function testDepartmentCanBeEdited()
    {
        $department = factory(Department::class)->make();

        $this->get(route('admin.departments.edit', $this->department));
        $response = $this->put(route('admin.departments.update', $this->department), [
            'name' => $department->name,
            'description' => $department->description,
            'internal' => $department->internal,
            'users' => [],
        ]);

        $response->assertRedirect(route('admin.departments.index'));
        $response->assertSessionHas('status', trans('department.updated'));
        $this->assertArraySubset([
            'name' => $department->name,
            'description' => $department->description,
        ], $this->department->fresh()->toArray());
    }

    public function testDepartmentCanBeDeleted()
    {
        $this->department->tickets()->save(factory(Ticket::class)->make());
        $response = $this->delete(route('admin.departments.destroy', $this->department));
        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->department->tickets()->delete();
        $response = $this->delete(route('admin.departments.destroy', $this->department));
        $response->assertRedirect(route('admin.departments.index'));
        $response->assertSessionHas('status', trans('department.deleted'));
        $this->assertDatabaseMissing('departments', ['id' => $this->department->id]);
    }
}
