<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Ticket;
use App\Models\TicketDepartment;
use App\Models\TicketPost;
use App\Models\User;
use App\Policies\ArticlePolicy;
use App\Policies\TicketDepartmentPolicy;
use App\Policies\TicketPolicy;
use App\Policies\TicketPostPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Article::class => ArticlePolicy::class,
        Ticket::class => TicketPolicy::class,
        TicketDepartment::class => TicketDepartmentPolicy::class,
        TicketPost::class => TicketPostPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
