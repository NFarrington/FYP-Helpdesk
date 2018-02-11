<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ArticleController extends Controller
{
    /**
     * The service.
     *
     * @var ArticleService
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @param ArticleService $service
     */
    public function __construct(ArticleService $service)
    {
        $this->middleware('auth');

        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $articles = $this->service->getViewableBy($request->user());

        return view('articles.index')->with('articles', $articles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Article::class);

        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Article::class);

        $attributes = $this->validate($request, $this->rules(), $this->messages());

        $article = $this->service->create($attributes);

        return redirect()->route('articles.show', $article)
            ->with('status', 'Article created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article $article
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Article $article)
    {
        $this->authorize('view', $article);

        return view('articles.view')->with('article', $article);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article $article
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Article $article)
    {
        $this->authorize('update', $article);

        return view('articles.edit')->with('article', $article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Article $article
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Article $article)
    {
        $this->authorize('update', $article);

        $attributes = $this->validate($request, $this->rules(), $this->messages());

        $this->service->update($article, $attributes);

        return redirect()->route('articles.show', $article)
            ->with('status', 'Article updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article $article
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        $this->service->delete($article);

        return redirect()->route('articles.index')
            ->with('status', 'Article deleted successfully.');
    }

    /**
     * Get the validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'title' => 'required|string|max:250',
            'content' => 'required|string|max:60000',
            'visible_from_date' => 'required_with:visible_from_time|nullable|date_format:Y-m-d',
            'visible_from_time' => 'required_with:visible_from_date|nullable|date_format:H:i',
            'visible_to_date' => 'required_with:visible_to_time|nullable|date_format:Y-m-d',
            'visible_to_time' => 'required_with:visible_to_date|nullable|date_format:H:i',
        ];
    }

    /**
     * Get the validation messages.
     *
     * @return array
     */
    protected function messages()
    {
        return [
            'visible_from_date.date_format' => 'The visible from date does not match the format YYYY-MM-DD.',
            'visible_from_time.date_format' => 'The visible from time does not match the format HH:MM.',
            'visible_to_date.date_format' => 'The visible to date does not match the format YYYY-MM-DD.',
            'visible_to_time.date_format' => 'The visible to time does not match the format HH:MM.',
        ];
    }
}
