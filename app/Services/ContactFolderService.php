<?php

namespace App\Services;

use App\Models\ContactFolder;

class ContactFolderService extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create_folder(array $data)
    {
        $folder = $this->user->contactFolders()->create($data);

        return [true, MCH_model_created("Contact folder"), [$folder], 200];
    }

    public function show_all_folders()
    {
        $folders = $this->user->contactFolders()->get()->toArray();

        return [true, MCH_model_retrieved("Contact folders"), $folders, 200];
    }

    public function update_folder(ContactFolder $contactFolder, array $data)
    {
        $contactFolder->update($data);

        $contactFolder->refresh();

        return [true, MCH_model_updated("Contact folder"), [$contactFolder], 200];
    }

    public function delete_folder(ContactFolder $contactFolder)
    {
        $contactFolder->delete();

        return [true, MCH_model_deleted("Contact folder"), [], 200];
    }
}
