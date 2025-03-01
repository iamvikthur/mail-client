<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuickMailRequest;
use App\Models\QuickMail;
use App\Services\QuickMailService;
use Illuminate\Http\Request;

class QuickMailController extends Controller
{
    public function __construct(private QuickMailService $quickMailService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        [$status, $message, $data, $status_code] = $this->quickMailService->show_all_quick_mails();

        return send_response($status, $data, $message, $status_code);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuickMailRequest $quickMailRequest)
    {
        [$status, $message, $data, $status_code] = $this->quickMailService->create_quick_mail(
            $quickMailRequest->validated()
        );

        return send_response($status, $data, $message, $status_code);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QuickMailRequest $quickMailRequest, QuickMail $quickMail)
    {
        [$status, $message, $data, $status_code] = $this->quickMailService->update_quick_mail(
            $quickMail,
            $quickMailRequest->validated()
        );

        return send_response($status, $data, $message, $status_code);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuickMail $quickMail)
    {
        [$status, $message, $data, $status_code] = $this->quickMailService->delete_quick_mail(
            $quickMail
        );

        return send_response($status, $data, $message, $status_code);
    }
}
