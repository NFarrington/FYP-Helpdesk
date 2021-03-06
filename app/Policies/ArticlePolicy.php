<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the article.
     *
     * @param User $user
     * @param Article|null $article
     * @return mixed
     */
    public function view(User $user, Article $article = null)
    {
        if ($article === null) {
            return $user->hasPermission('articles.view');
        }

        $standardUser = $article->isPublished();
        $elevatedUser = $user->hasPermission('articles.view');

        return $standardUser || $elevatedUser;
    }

    /**
     * Determine whether the user can create articles.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('articles.create');
    }

    /**
     * Determine whether the user can update the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function update(User $user, Article $article)
    {
        return $user->hasPermission('articles.update');
    }

    /**
     * Determine whether the user can delete the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function delete(User $user, Article $article)
    {
        return $user->hasPermission('articles.delete');
    }

    /**
     * Determine whether the user can add a comment to the article.
     *
     * @param User $user
     * @param Article $article
     * @return mixed
     */
    public function createComment(User $user, Article $article)
    {
        return $this->view($user, $article);
    }
}
