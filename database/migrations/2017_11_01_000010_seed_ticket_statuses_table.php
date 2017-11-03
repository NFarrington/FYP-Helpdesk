<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedTicketStatusesTable extends Migration
{
    private $ticket_statuses = [
        ['name' => 'Open', 'state' => 1],
        ['name' => 'With Customer', 'state' => 2],
        ['name' => 'Closed', 'state' => 3],
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('ticket_statuses')->insert($this->ticket_statuses);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $statusNames = collect($this->ticket_statuses)->pluck('name');

        DB::table('ticket_statuses')->whereIn('name', $statusNames)->delete();
    }
}
