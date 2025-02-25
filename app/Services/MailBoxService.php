<?php

namespace App\Services;

use App\Enums\MailboxStateEnum;
use App\Mail\TestMailBoxMailable;
use App\Models\MailBox;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailBoxService extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create_mailbox(array $mailbox): array
    {
        $this->user->mailBoxes()->create($mailbox);

        $userMailboxs = $this->user->mailBoxes()->get()->toArray();

        return [true, MCH_model_created("Mailbox"), $userMailboxs, 200];
    }

    public function show_all()
    {
        $mailboxes = $this->user->mailBoxes()->get()->toArray();

        return [true, MCH_model_retrieved("Mailboxes"), $mailboxes, 200];
    }

    public function update_mailbox(MailBox $mailBox, array $updateData)
    {
        $mailBox->update($updateData);

        $mailBox->refresh();

        return [true, MCH_model_updated("Mailbox"), [$mailBox], 200];
    }

    public function delete_mailbox(MailBox $mailBox)
    {
        $mailBox->delete();

        return [true, MCH_model_deleted("Mailbox"), [], 200];
    }

    public function check_mailbox_state(MailBox $mailBox)
    {
        $toMail = "iamvikthur@gmail.com";
        $smtpConfig = $mailBox->smtp_details();
        $smtpConfig['transport'] = 'smtp';
        $fromAddress = $mailBox->smtp_username;
        $fromName = $mailBox->title;

        try {
            Mail::build($smtpConfig)
                ->to($toMail)
                ->sendNow(new TestMailBoxMailable($fromAddress, $fromName));

            $mailBox->update(['state' => MailboxStateEnum::ONLINE]);
            return [true, "Mailbox is online", [$mailBox], 200];
        } catch (\Throwable $th) {
            // Log::error($th->getMessage());
            $mailBox->update(['state' => MailboxStateEnum::OFFLINE]);
            return [true, $th->getMessage(), [$mailBox], 500];
            //throw $th;
        }
    }
}
