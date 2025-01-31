<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Http\Services\ContactService;
use App\Models\Contact;
use App\Models\ContactList;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(private ContactService $contactService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(ContactList $contactList)
    {
        [$status, $message, $data, $status_code] = $this->contactService->show_all_contacts(
            $contactList
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
    public function store(ContactRequest $contactRequest, ContactList $contactList)
    {
        [$status, $message, $data, $status_code] = $this->contactService->create_contact(
            $contactList,
            $contactRequest->validated()
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactRequest $contactRequest, Contact $contact)
    {
        [$status, $message, $data, $status_code] = $this->contactService->update_contact(
            $contact,
            $contactRequest->validated()
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        [$status, $message, $data, $status_code] = $this->contactService->delete_contact(
            $contact
        );
    }
}
