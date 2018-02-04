<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleComment;
use Illuminate\Http\Request;

class ArticleCommentController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, Article $article)
    {
        $this->authorize('create-comment', $article);

        $attributes = $this->validate($request, [
            'content' => 'required|string|max:1000',
        ]);

        /** @var ArticleComment $articleComment */
        $articleComment = ArticleComment::make($attributes);
        $articleComment->article()->associate($article);
        $articleComment->user()->associate($request->user());
        $articleComment->save();

        return redirect()->route('articles.show', $article)->with('status', 'Comment added successfully.');
    }
}
