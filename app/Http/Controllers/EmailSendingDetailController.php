<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailSendinDetailRequest;
use App\Http\Services\EmailSendingDetailService;
use App\Models\EmailSendingDetail;
use Illuminate\Http\Request;

class EmailSendingDetailController extends Controller
{
    public function __construct(private EmailSendingDetailService $emailSendingDetailService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->emailSendingDetailService->showAll();
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
    public function store(EmailSendinDetailRequest $emailSendinDetailRequest)
    {
        $response = $this->emailSendingDetailService->createDetail(
            $emailSendinDetailRequest->validated()
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
    public function update(EmailSendinDetailRequest $emailSendinDetailRequest, EmailSendingDetail $emailSendingDetail)
    {
        $response = $this->emailSendingDetailService->updateDetails(
            $emailSendingDetail,
            $emailSendinDetailRequest->validated()
        );
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailSendingDetail $emailSendingDetail)
    {
        $response = $this->emailSendingDetailService->destroyDetails($emailSendingDetail);
        return $response;
    }
}
