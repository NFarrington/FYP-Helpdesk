<?php

class TicketsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agents = \App\Models\User::whereHas('roles', function ($query) {
            $query->where('key', 'agent');
        })->inRandomOrder()->limit(20)->get();
        $users = \App\Models\User::query()->inRandomOrder()->limit(20)->get();
        foreach ($users as $user) {
            $tickets = factory(\App\Models\Ticket::class, 5)->create(['user_id' => $user->id]);

            foreach ($tickets as $ticket) {
                $ticket->posts()->saveMany(factory(\App\Models\TicketPost::class, 2)->make(['user_id' => $agents->random()->id, 'ticket_id' => $ticket->id]));
                $ticket->posts()->saveMany(factory(\App\Models\TicketPost::class, 5)->make(['user_id' => $user->id, 'ticket_id' => $ticket->id]));
                $ticket->posts()->saveMany(factory(\App\Models\TicketPost::class, 2)->make(['user_id' => $agents->random()->id, 'ticket_id' => $ticket->id]));
            }
        }
    }
}
