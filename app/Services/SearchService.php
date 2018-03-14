<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use App\Repositories\ArticleRepository;
use App\Repositories\TicketRepository;
use App\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchService extends Service
{
    /**
     * The article repository.
     *
     * @var ArticleRepository
     */
    protected $articleRepo;

    /**
     * The ticket repository.
     *
     * @var TicketRepository
     */
    protected $ticketRepo;

    /**
     * The user repository.
     *
     * @var UserRepository
     */
    protected $userRepo;

    /**
     * Initialise the service.
     *
     * @param ArticleRepository $articleRepo
     * @param TicketRepository $ticketRepo
     * @param UserRepository $userRepo
     */
    public function __construct(ArticleRepository $articleRepo, TicketRepository $ticketRepo, UserRepository $userRepo)
    {
        $this->articleRepo = $articleRepo;
        $this->ticketRepo = $ticketRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * Search for articles matching the given query.
     *
     * @param User $user
     * @param string[] $searchQuery
     * @return \Illuminate\Database\Eloquent\Collection|Article[]
     */
    public function searchArticles($user, $searchQuery)
    {
        $articles = Article::query();

        foreach ($searchQuery as $keyword) {
            $articles->where(function ($query) use ($keyword) {
                $query->where('title', 'REGEXP', "[[:<:]]{$keyword}[[:>:]]")
                    ->orWhere('content', 'REGEXP', "[[:<:]]{$keyword}[[:>:]]");
            });
        }

        return $articles->get()->filter(function ($article) use ($user) {
            return $user->can('view', $article);
        });
    }

    /**
     * Search for tickets matching the given query.
     *
     * @param User $user
     * @param string[] $searchQuery
     * @return \Illuminate\Database\Eloquent\Collection|Ticket[]
     */
    public function searchTickets($user, $searchQuery)
    {
        $tickets = $user->can('agent')
            ? Ticket::query()
            : $user->tickets();

        foreach ($searchQuery as $keyword) {
            $tickets->where(function ($query) use ($keyword) {
                $query->where('summary', 'REGEXP', "[[:<:]]{$keyword}[[:>:]]")
                    ->orWhereHas('posts', function ($query) use ($keyword) {
                        $query->where('content', 'REGEXP', "[[:<:]]{$keyword}[[:>:]]");
                    });
            });
        }

        $tickets = $tickets->get();

        return $user->can('agent')
            ? $tickets->filter(function ($ticket) use ($user) {
                return $user->can('viewAsAgent', $ticket);
            })
            : $tickets->filter(function ($ticket) use ($user) {
                return $user->can('view', $ticket);
            });
    }

    /**
     * Search for users matching the given query.
     *
     * @param User $user
     * @param string[] $searchQuery
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchUsers($user, $searchQuery)
    {
        if (!$user->hasRole(Role::admin())) {
            return new LengthAwarePaginator(collect(), 0, 1);
        }

        $users = User::query();

        foreach ($searchQuery as $keyword) {
            $users->where(function ($query) use ($keyword) {
                $query->where('name', 'REGEXP', "[[:<:]]{$keyword}[[:>:]]")
                    ->orWhere('email', 'REGEXP', "[[:<:]]{$keyword}[[:>:]]");
            });
        }

        return $users->paginate(20);
    }
}
