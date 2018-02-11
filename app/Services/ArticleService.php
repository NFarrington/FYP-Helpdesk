<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use App\Repositories\ArticleRepository;

class ArticleService extends Service
{
    /**
     * The repository.
     *
     * @var ArticleRepository
     */
    protected $repository;

    /**
     * Initialise the service.
     *
     * @param ArticleRepository $repository
     */
    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Create a new article.
     *
     * @param array $attributes
     * @return Article
     */
    public function create(array $attributes)
    {
        return Article::create($this->formatAttributes($attributes));
    }

    /**
     * Update an article.
     *
     * @param Article $article
     * @param array $attributes
     * @return Article
     */
    public function update(Article $article, array $attributes)
    {
        return tap($article)->update($this->formatAttributes($attributes));
    }

    /**
     * Delete an article.
     *
     * @param Article $article
     * @return void
     * @throws \Exception
     */
    public function delete(Article $article)
    {
        $article->delete();
    }

    /**
     * Get all model instances the user can view.
     *
     * @param User $user
     * @return \App\Models\Article[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getViewableBy(User $user)
    {
        return $user->can('view', Article::class)
            ? $this->repository->getAll()
            : $this->repository->getPublished();
    }

    /**
     * Format the attributes.
     *
     * @return array
     */
    protected function formatAttributes(array $attributes)
    {
        $visibleFrom = sprintf(
            '%s %s',
            array_pull($attributes, 'visible_from_date'),
            array_pull($attributes, 'visible_from_time')
        );
        $visibleTo = sprintf(
            '%s %s',
            array_pull($attributes, 'visible_to_date'),
            array_pull($attributes, 'visible_to_time')
        );
        $visibility = [
            'visible_from' => !ctype_space($visibleFrom) ? $visibleFrom.':00' : null,
            'visible_to' => !ctype_space($visibleTo) ? $visibleTo.':00' : null,
        ];

        return $attributes + $visibility;
    }
}
