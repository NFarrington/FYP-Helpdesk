<?php

namespace App\Http\Controllers\Api;

use App\Models\Announcement;
use App\Services\AnnouncementService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class AnnouncementController extends Controller
{
    /**
     * The service.
     *
     * @var AnnouncementService
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @param AnnouncementService $service
     */
    public function __construct(AnnouncementService $service)
    {
        parent::__construct();

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
        $announcements = $this->service->getViewableBy($request->user());

        return Resource::collection($announcements);
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
        $this->authorize('create', Announcement::class);

        $attributes = $this->validate($request, $this->rules());

        $announcement = $this->service->create($attributes, $request->user());

        return response()->json($announcement->attributesToArray(), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Announcement $announcement
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Announcement $announcement)
    {
        $this->authorize('view', $announcement);

        return response()->json($announcement->attributesToArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Announcement $announcement
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Announcement $announcement)
    {
        $this->authorize('update', $announcement);

        $attributes = $this->validate($request, $this->rules());

        $this->service->update($announcement, $attributes);

        return response()->json($announcement->attributesToArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Announcement $announcement
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Announcement $announcement)
    {
        $this->authorize('delete', $announcement);

        $this->service->delete($announcement);

        return response()->json(['success' => true]);
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
            'status' => 'required|integer|between:0,2',
        ];
    }
}
