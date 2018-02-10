<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\Article;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketPost;
use App\Models\User;
use App\Policies\AnnouncementPolicy;
use App\Policies\ArticlePolicy;
use App\Policies\DepartmentPolicy;
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
        Announcement::class => AnnouncementPolicy::class,
        Article::class => ArticlePolicy::class,
        Department::class => DepartmentPolicy::class,
        Ticket::class => TicketPolicy::class,
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
