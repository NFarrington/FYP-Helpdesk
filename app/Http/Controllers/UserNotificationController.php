<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    /**
     * The service.
     *
     * @var \App\Services\NotificationService
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\NotificationService $service
     */
    public function __construct(NotificationService $service)
    {
        $this->middleware('auth');

        $this->service = $service;
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
            'name' => 'required|string|max:50',
            'uri' => ['required', 'url', 'max:250', 'regex:/https:\/\/hooks.slack.com\/services\/[A-Za-z0-9]*\/[A-Za-z0-9]*\/[A-Za-z0-9]/'],
            'recipient' => ['required', 'string', 'max:250', 'regex:/^#|^@/'],
        ], [
            'uri.regex' => 'Must be in the format https://hooks.slack.com/services/X/Y/Z',
        ]);

        $this->service->createWebhook($attributes, $request->user());

        return redirect()->route('profile.notifications.show')
            ->with('status', 'Slack webhook added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return view('user-notifications.edit')->with('user', $request->user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = [];
        foreach (NotificationService::USER_NOTIFICATIONS as $index => $notification) {
            $rules["{$notification}_email"] = 'required|boolean';
            $rules["{$notification}_slack"] = 'nullable|exists:slack_webhooks,id';
        }

        if ($request->user()->can('agent')) {
            foreach (NotificationService::AGENT_NOTIFICATIONS as $index => $notification) {
                $rules["{$notification}_email"] = 'required|boolean';
                $rules["{$notification}_slack"] = 'nullable|exists:slack_webhooks,id';
            }
        }

        $attributes = $this->validate($request, $rules);

        $this->service->update($request->user(), $attributes);

        return redirect()->route('profile.notifications.show')
            ->with('status', 'Notification settings updated.');
    }
}
