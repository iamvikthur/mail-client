<?php

namespace App\Http\Services;

use App\Models\EmailSendingDetail;
use App\Models\MailBox;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class MailBoxService
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

    public function updateDetails(MailBox $mailBox, array $updatedData)
    {
        $mailBox->update($updatedData);

        return [true, "Email sending detail updated", [$mailBox], 200];
    }

    public function destroyDetails(MailBox $mailBox)
    {
        $this->user->emailSendingDetails()->find($mailBox->id)->delete();

        return [true, "Email sending detail deleted", [$mailBox], 200];
    }
}
