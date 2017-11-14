<?php

use Illuminate\Database\Migrations\Migration;

class SeedTicketDepartmentsTable extends Migration
{
    private $ticket_departments = [
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
        DB::table('ticket_departments')->insert($this->ticket_departments);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $statusNames = collect($this->ticket_departments)->pluck('name');

        DB::table('ticket_departments')->whereIn('name', $statusNames)->delete();
    }
}
