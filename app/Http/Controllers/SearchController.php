<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Search resources with the given query.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $validator = $this->getValidationFactory()->make($request->all(), [
            'q' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('home');
        }

        $searchQuery = explode(' ', $request->input('q'));

        $tickets = $this->searchTickets($request->user(), $searchQuery);
        $articles = $this->searchArticles($request->user(), $searchQuery);
        $users = $this->searchUsers($request->user(), $searchQuery);

        return view('search.index')->with([
            'tickets' => $tickets,
            'articles' => $articles,
            'users' => $users,
        ]);
    }

    /**
     * Search for tickets matching the given query.
     *
     * @param User $user
     * @param string[] $searchQuery
     * @return \Illuminate\Database\Eloquent\Collection|Ticket[]
     */
    protected function searchTickets($user, $searchQuery)
    {
        $tickets = $user->hasRole(Role::agent())
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

        return $user->hasRole(Role::agent())
            ? $tickets->filter(function ($ticket) use ($user) {
                return $user->can('viewAsAgent', $ticket);
            })
            : $tickets->filter(function ($ticket) use ($user) {
                return $user->can('view', $ticket);
            });
    }

    /**
     * Search for articles matching the given query.
     *
     * @param User $user
     * @param string[] $searchQuery
     * @return \Illuminate\Database\Eloquent\Collection|Article[]
     */
    protected function searchArticles($user, $searchQuery)
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
     * Search for users matching the given query.
     *
     * @param User $user
     * @param string[] $searchQuery
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function searchUsers($user, $searchQuery)
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
