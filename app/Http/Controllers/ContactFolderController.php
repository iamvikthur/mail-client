<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFolderRequest;
use App\Services\ContactFolderService;
use App\Models\ContactFolder;
use Illuminate\Http\Request;

class ContactFolderController extends Controller
{
    public function __construct(private ContactFolderService $contactFolderService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        [$status, $message, $data, $status_code] = $this->contactFolderService->show_all_folders();
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
    public function store(ContactFolderRequest $contactFolderRequest)
    {
        [$status, $message, $data, $status_code] = $this->contactFolderService->create_folder(
            $contactFolderRequest->validated()
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactFolder $contactFolder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactFolder $contactFolder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactFolderRequest $contactFolderRequest, ContactFolder $contactFolder)
    {
        [$status, $message, $data, $status_code] = $this->contactFolderService->update_folder(
            $contactFolder,
            $contactFolderRequest->validated()
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactFolder $contactFolder)
    {
        [$status, $message, $data, $status_code] = $this->contactFolderService->delete_folder(
            $contactFolder
        );
    }
}
