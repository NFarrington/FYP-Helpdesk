<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\Article;
use App\Models\Department;
use App\Models\Permission;
use App\Models\Role;
use App\Models\SlackWebhook;
use App\Models\Ticket;
use App\Models\TicketPost;
use App\Models\User;
use App\Policies\AnnouncementPolicy;
use App\Policies\ArticlePolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\SlackWebhookPolicy;
use App\Policies\TicketPolicy;
use App\Policies\TicketPostPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

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
        Permission::class => PermissionPolicy::class,
        Role::class => RolePolicy::class,
        SlackWebhook::class => SlackWebhookPolicy::class,
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

        Passport::routes();
    }
}
