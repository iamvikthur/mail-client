<?php

namespace App\Services;

use App\Models\ContactFolder;
use App\Models\ContactList;

class ContactListService extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create_contact_list(ContactFolder $contactFolder, array $data): array
    {
        $contactList = $contactFolder->contactLists()->create($data);

        return [true, MCH_model_created("Contact list"), [$contactList], 200];
    }

    public function show_all_contact_lists(ContactFolder $contactFolder): array
    {
        $contactLists = $contactFolder->contactLists;

        return [true, MCH_model_retrieved("Contact lists"), $contactLists, 200];
    }

    public function update_contact_list(ContactList $contactList, array $data)
    {
        $updatedContactList = $contactList->update($data);

        return [true, MCH_model_updated("Contact list"), [$updatedContactList], 200];
    }

    public function delete_contact_list(ContactList $contactList)
    {
        $contactList->delete();

        return [true, MCH_model_created("Contact list"), [], 200];
    }
}
