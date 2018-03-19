<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Laravel\Passport\Token;

class UserApiController extends Controller
{
    /**
     * The service.
     *
     * @var \App\Services\ApiService
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\ApiService $service
     */
    public function __construct(ApiService $service)
    {
        $this->middleware('auth');

        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keys = $this->service->getOwnedBy($request->user());
        $paginatedKeys = $this->paginate($keys, 20, ['path' => route('profile.api.index')]);

        return view('user-api.index')->with('keys', $paginatedKeys);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user-api.create')->with('token', new Token());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $this->validate($request, [
            'name' => 'required|string|max:250',
        ]);

        $token = $this->service->create($attributes, $request->user());

        return redirect()->route('profile.api.index')
            ->with([
                'status' => trans('token.created'),
                'newToken' => $token->accessToken,
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Laravel\Passport\Token $token
     * @return \Illuminate\Http\Response
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Token $token)
    {
        $this->authorize('delete', $token);

        $token->delete();

        return redirect()->route('profile.api.index')
            ->with('status', trans('token.deleted'));
    }
}
