<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\User;
use App\Repositories\ArticleCommentRepository;

class ArticleCommentService extends Service
{
    /**
     * The repository.
     *
     * @var ArticleCommentRepository
     */
    protected $repository;

    /**
     * Initialise the service.
     *
     * @param ArticleCommentRepository $repository
     */
    public function __construct(ArticleCommentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Create a new article comment.
     *
     * @param array $attributes
     * @param Article $article
     * @param User $user
     * @return ArticleComment
     */
    public function create(array $attributes, Article $article, User $user)
    {
        $articleComment = new ArticleComment($attributes);
        $articleComment->article()->associate($article);
        $articleComment->user()->associate($user);
        $articleComment->save();

        return $articleComment;
    }
}
