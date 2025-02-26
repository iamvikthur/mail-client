<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Services\ContactService;
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

        return send_response($status, $data, $message, $status_code);
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

        return send_response($status, $data, $message, $status_code);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactRequest $contactRequest, ContactList $contactList, Contact $contact)
    {
        [$status, $message, $data, $status_code] = $this->contactService->update_contact(
            $contact,
            $contactRequest->validated()
        );

        return send_response($status, $data, $message, $status_code);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactList $contactList, Contact $contact)
    {
        [$status, $message, $data, $status_code] = $this->contactService->delete_contact(
            $contact
        );

        return send_response($status, $data, $message, $status_code);
    }
}
