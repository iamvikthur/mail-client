<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactListRequest;
use App\Services\ContactListService;
use App\Models\ContactFolder;
use App\Models\ContactList;
use Illuminate\Http\Request;

class ContactListController extends Controller
{
    public function __construct(private ContactListService $contactListService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(ContactFolder $contactFolder)
    {
        [$status, $message, $data, $status_code] = $this->contactListService->show_all_contact_lists(
            $contactFolder
        );

        return send_response($status, $data, $message, $status_code);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactListRequest $contactListRequest, ContactFolder $contactFolder)
    {
        [$status, $message, $data, $status_code] = $this->contactListService->create_contact_list(
            $contactFolder,
            $contactListRequest->validated()
        );

        return send_response($status, $data, $message, $status_code);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactListRequest $contactListRequest, ContactFolder $contactFolder, ContactList $contactList)
    {
        [$status, $message, $data, $status_code] = $this->contactListService->update_contact_list(
            $contactList,
            $contactListRequest->validated()
        );

        return send_response($status, $data, $message, $status_code);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactFolder $contactFolder, ContactList $contactList)
    {
        [$status, $message, $data, $status_code] = $this->contactListService->delete_contact_list(
            $contactList
        );

        return send_response($status, $data, $message, $status_code);
    }
}
