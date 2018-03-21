<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Reauthenticate a user for extra security.
     *
     * @param string $email
     * @param string $password
     * @throws ValidationException
     */
    protected function reauthenticate(string $email, string $password)
    {
        if (!auth()->validate(['email' => $email, 'password' => $password])) {
            throw ValidationException::withMessages([
                'password' => [trans('auth.failed')],
            ]);
        }
    }

    /**
     * Paginate a collection of Eloquent models.
     *
     * @param \Illuminate\Support\Collection $items
     * @param int $perPage
     * @param array $options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function paginate(Collection $items, int $perPage = 10, array $options = [])
    {
        $currentPage = $this->getPaginatorPage(app(Request::class), $options);

        return new LengthAwarePaginator(
            $items->forPage($currentPage, $perPage),
            $items->count(),
            $perPage, $currentPage, $options
        );
    }

    /**
     * Calculate the current pagination page.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $options
     * @return int|null|string
     */
    private function getPaginatorPage(Request $request, array $options)
    {
        $pageName = array_get($options, 'pageName') ?: 'page';
        $page = $request->input($pageName);

        return is_numeric($page) ? $page : null;
    }
}
