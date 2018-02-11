<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

class ArticleRepository extends Repository
{
    /**
     * The order to sort the results by.
     *
     * @var string
     */
    protected $sortOrder = ['title', 'ASC'];

    /**
     * The model.
     *
     * @var Article
     */
    protected $model;

    /**
     * Initialise the repository.
     *
     * @param Article $model
     */
    public function __construct(Article $model)
    {
        $this->model = $model;
    }

    /**
     * Return all published articles.
     *
     * @return Collection|Article[]
     */
    public function getPublished()
    {
        return $this->model->published()->orderBy(...$this->sortOrder)->get();
    }
}
