<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\ContactList;

class ContactService extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create_contact(ContactList $contactList, array $data): array
    {
        $contactList = $contactList->contacts()->create($data);

        return [true, MCH_model_created("Contact"), [$contactList], 200];
    }

    public function show_all_contacts(ContactList $contactList): array
    {
        $contactLists = $contactList->contacts;

        return [true, MCH_model_retrieved("Contacts"), $contactLists, 200];
    }

    public function update_contact(Contact $contact, array $data)
    {
        $updatedContactList = $contact->update($data);

        return [true, MCH_model_updated("Contact"), [$updatedContactList], 200];
    }

    public function delete_contact(Contact $contact)
    {
        $contact->delete();

        return [true, MCH_model_created("Contact"), [], 200];
    }
}
