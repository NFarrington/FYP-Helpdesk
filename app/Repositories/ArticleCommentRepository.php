<?php

namespace App\Repositories;


use App\Models\ArticleComment;

class ArticleCommentRepository extends Repository
{
    /**
     * The order to sort the results by.
     *
     * @var string
     */
    protected $sortOrder = ['created_at', 'DESC'];

    /**
     * The model.
     *
     * @var ArticleComment
     */
    protected $model;

    /**
     * EloquentRepository constructor.
     *
     * @param ArticleComment $model
     */
    public function __construct(ArticleComment $model)
    {
        $this->model = $model;
    }
}
