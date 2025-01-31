<?php

namespace App\Services;

use App\Models\EmailSendingDetail;
use App\Models\MailBox;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class MailBoxService extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create_mailbox(array $mailbox): array
    {
        $this->user->mailBoxes()->create($mailbox);

        $mailbox = $this->user->load("mailboxes");

        return [true, MCH_model_created("Mailbox"), [$mailbox], 200];
    }

    public function show_all()
    {

        $mailboxes = $this->user->mailBoxes;

        return [true, MCH_model_retrieved("Mailboxes"), $mailboxes, 200];
    }

    public function update_mailbox(MailBox $mailBox, array $updateData)
    {
        $updatedMailbox = $mailBox->update($updateData);

        return [true, MCH_model_updated("Mailbox"), [$updatedMailbox], 200];
    }

    public function delete_mailbox(MailBox $mailBox)
    {
        $mailBox->delete();

        return [true, MCH_model_deleted("Mailbox"), [], 200];
    }
}
