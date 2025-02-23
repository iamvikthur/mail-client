<?php

namespace App\Http\Controllers;

use App\Http\Requests\MailBoxRequest;
use App\Services\MailBoxService;
use App\Models\MailBox;

class MailBoxController extends Controller
{
    public function __construct(private MailBoxService $mailBoxService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        [$status, $message, $data, $status_code] = $this->mailBoxService->show_all();

        return send_response($status, $data, $message, $status_code);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MailBoxRequest $mailBoxRequest)
    {
        [$status, $message, $data, $status_code] = $this->mailBoxService->create_mailbox(
            $mailBoxRequest->validated()
        );

        return send_response($status, $data, $message, $status_code);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MailBoxRequest $mailBoxRequest, MailBox $mailBox)
    {
        [$status, $message, $data, $status_code] = $this->mailBoxService->update_mailbox(
            $mailBox,
            $mailBoxRequest->validated()
        );

        return send_response($status, $data, $message, $status_code);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MailBox $mailBox)
    {
        [$status, $message, $data, $status_code] = $this->mailBoxService->delete_mailbox($mailBox);

        return send_response($status, $data, $message, $status_code);
    }
}
