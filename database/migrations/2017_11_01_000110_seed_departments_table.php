<?php

use Illuminate\Database\Migrations\Migration;

class SeedDepartmentsTable extends Migration
{
    private $departments = [
        ['name' => 'Sales', 'description' => 'Sales enquiries', 'internal' => 0],
        ['name' => 'Support', 'description' => 'Technical support', 'internal' => 0],
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('departments')->insert($this->departments);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $statusNames = collect($this->departments)->pluck('name');

        DB::table('departments')->whereIn('name', $statusNames)->delete();
    }
}
