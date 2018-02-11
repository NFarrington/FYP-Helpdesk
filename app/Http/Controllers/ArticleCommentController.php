<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleComment;
use App\Services\ArticleCommentService;
use Illuminate\Http\Request;

class ArticleCommentController extends Controller
{
    /**
     * The service.
     *
     * @var ArticleCommentService
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @param ArticleCommentService $service
     */
    public function __construct(ArticleCommentService $service)
    {
        $this->middleware('auth');

        $this->service = $service;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Article $article
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, Article $article)
    {
        $this->authorize('create-comment', $article);

        $attributes = $this->validate($request, $this->rules());

        $this->service->create($attributes, $article, $request->user());

        return redirect()->route('articles.show', $article)->with('status', 'Comment added successfully.');
    }

    /**
     * Get the validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'content' => 'required|string|max:1000',
        ];
    }
}
