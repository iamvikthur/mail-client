<?php

namespace App\Http\Services;

use App\Models\EmailSendingDetail;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class EmailSendingDetailService
{
    private User $user;
    public function __construct()
    {
        $authenticatable = Auth::user();

        if ($authenticatable == null) {
            throw new AuthenticationException("This service requires an authenticated user");
        }

        $user = User::find($authenticatable->id)->first();
        $this->user = $user;
    }
    public function createDetail(array $userDetails): array
    {
        $this->user->emailSendingDetails()->create($userDetails);
        $this->user->load("emailSendingDetails");

        return [true, "Email sending details saved", [$userDetails], 200];
    }

    public function showAll()
    {

        $emailSendingDetails = $this->user->emailSendingDetails;

        return [true, "Email sending detail updated", $emailSendingDetails, 200];
    }

    public function updateDetails(EmailSendingDetail $emailSendingDetail, array $updatedData)
    {
        $emailSendingDetail->update($updatedData);

        return [true, "Email sending detail updated", [$emailSendingDetail], 200];
    }

    public function destroyDetails(EmailSendingDetail $emailSendingDetail)
    {
        $this->user->emailSendingDetails()->find($emailSendingDetail->id)->delete();

        return [true, "Email sending detail deleted", [$emailSendingDetail], 200];
    }
}
