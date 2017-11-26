<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->hasPermission('articles.update')) {
            $articles = Article::all()->filter(function ($value, $key) use ($request) {
                return $request->user()->can('update', $value);
            });
        } else {
            $articles = Article::published()->get();
        }

        return view('articles.index')->with('articles', $articles->sortBy('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Article::class);

        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Article::class);

        $this->validate($request, [
            'title' => 'required|string|max:250',
            'content' => 'required|string|max:60000',
            'visible_from_date' => 'required_with:visible_from_time|nullable|date_format:Y-m-d',
            'visible_from_time' => 'required_with:visible_from_date|nullable|date_format:H:i',
            'visible_to_date' => 'required_with:visible_to_time|nullable|date_format:Y-m-d',
            'visible_to_time' => 'required_with:visible_to_date|nullable|date_format:H:i',
        ], [
            'visible_from_date.date_format' => 'The visible from date does not match the format YYYY-MM-DD.',
            'visible_from_time.date_format' => 'The visible from time does not match the format HH:MM.',
            'visible_to_date.date_format' => 'The visible to date does not match the format YYYY-MM-DD.',
            'visible_to_time.date_format' => 'The visible to time does not match the format HH:MM.',
        ]);

        $visibleFrom = "{$request->input('visible_from_date')} {$request->input('visible_from_time')}";
        $visibleTo = "{$request->input('visible_to_date')} {$request->input('visible_to_time')}";
        $visibility = [
            'visible_from' => !ctype_space($visibleFrom) ? $visibleFrom : null,
            'visible_to' => !ctype_space($visibleTo) ? $visibleTo : null,
        ];

        $article = Article::create(array_merge($request->only('title', 'content') + $visibility));

        return redirect(route('articles.show', $article));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        $this->authorize('view', $article);

        return view('articles.view')->with('article', $article);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }
}
