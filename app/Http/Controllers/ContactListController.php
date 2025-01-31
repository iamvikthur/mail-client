<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactListRequest;
use App\Http\Services\ContactListService;
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
    public function store(ContactListRequest $contactListRequest, ContactFolder $contactFolder)
    {
        [$status, $message, $data, $status_code] = $this->contactListService->create_contact_list(
            $contactFolder,
            $contactListRequest->validated()
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactList $contactList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactList $contactList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactListRequest $contactListRequest, ContactList $contactList)
    {
        [$status, $message, $data, $status_code] = $this->contactListService->update_contact_list(
            $contactList,
            $contactListRequest->validated()
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactList $contactList)
    {
        [$status, $message, $data, $status_code] = $this->contactListService->delete_contact_list(
            $contactList
        );
    }
}
