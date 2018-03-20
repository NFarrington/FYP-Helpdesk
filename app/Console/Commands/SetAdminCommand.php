<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class SetAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:set-admin {user-id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upgrade a user to administrator.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userId = $this->argument('user-id') ?? $this->getUserId();
        $user = User::findOrFail($userId); /* @var User $user */

        if ($user->can('admin')) {
            $this->error("{$user->name} is already an administrator.");

            return 1;
        }

        $user->roles()->attach(Role::admin());

        $this->info("{$user->name} is now an administrator.");

        return 0;
    }

    /**
     * Ask the user for the new administrator's user ID.
     *
     * @return string
     * @codeCoverageIgnore
     */
    protected function getUserId()
    {
        $users = User::all();
        foreach ($users as $user) {
            $outputMethod = $user->can('admin') ? 'error' : 'line';
            $outputString = "ID {$user->id} - {$user->name}";

            $this->$outputMethod($outputString);
        }

        return $this->ask('Enter the ID for the user you wish to upgrade: ');
    }
}
