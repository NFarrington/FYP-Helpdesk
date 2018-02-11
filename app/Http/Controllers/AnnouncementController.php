<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Repositories\AnnouncementRepository;
use App\Services\AnnouncementService;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * The announcement service.
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
        $this->middleware('auth');

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

        return view('announcements.index')->with('announcements', $announcements);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Announcement::class);

        return view('announcements.create')->with('announcement', new Announcement());
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

        return redirect()->route('announcements.show', $announcement)
            ->with('status', 'Announcement created successfully.');
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

        return view('announcements.view')->with('announcement', $announcement);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Announcement $announcement
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Announcement $announcement)
    {
        $this->authorize('update', $announcement);

        return view('announcements.edit')->with('announcement', $announcement);
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

        return redirect()->route('announcements.show', $announcement)
            ->with('status', 'Announcement updated successfully.');
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

        return redirect()->route('announcements.index')
            ->with('status', 'Announcement deleted successfully.');
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
