<?php

namespace App\Http\Controllers;

use App\Http\Requests\MailBoxRequest;
use App\Http\Services\MailBoxService;
use App\Models\MailBox;
use Illuminate\Http\Request;

class MailBoxController extends Controller
{
    public function __construct(private MailBoxService $mailBoxService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->mailBoxService->show_all();
        return $response;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MailBoxRequest $mailBoxRequest)
    {
        $response = $this->mailBoxService->create_mailbox(
            $mailBoxRequest->validated()
        );
        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MailBoxRequest $mailBoxRequest, MailBox $mailBox)
    {
        $response = $this->mailBoxService->update_mailbox(
            $mailBox,
            $mailBoxRequest->validated()
        );
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MailBox $mailBox)
    {
        $response = $this->mailBoxService->delete_mailbox($mailBox);
        return $response;
    }
}
