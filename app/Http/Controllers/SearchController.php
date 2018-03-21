<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * The service.
     *
     * @var SearchService
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @param SearchService $service
     */
    public function __construct(SearchService $service)
    {
        $this->middleware('auth');

        $this->service = $service;
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

        $tickets = $this->service->searchTickets($request->user(), $searchQuery);
        $articles = $this->service->searchArticles($request->user(), $searchQuery);
        $users = $this->service->searchUsers($request->user(), $searchQuery);

        return view('search.index')->with([
            'tickets' => $this->paginate($tickets, 10, ['pageName' => 'tickets', 'path' => route('search')])->appends('q', $request->input('q')),
            'articles' => $this->paginate($articles, 10, ['pageName' => 'articles', 'path' => route('search')])->appends('q', $request->input('q')),
            'users' => $this->paginate($users, 10, ['pageName' => 'users', 'path' => route('search')])->appends('q', $request->input('q')),
        ]);
    }
}
