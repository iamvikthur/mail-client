<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImapRequest;
use App\Models\MailBox;
use App\Services\ImapService;
use Illuminate\Http\Request;

class ImapController extends Controller
{
    protected function __construct(private ImapService $imapService) {}
    public function index(MailBox $mailBox)
    {
        [$status, $message, $data, $status_code] = $this->imapService->getFolders($mailBox);
    }

    public function create_folder(ImapRequest $imapRequest, MailBox $mailBox)
    {
        $name = $imapRequest->validated()['name'];
        [$status, $message, $data, $status_code] = $this->imapService->createFolder($mailBox, $name);
    }

    public function get_mails(Request $request, MailBox $mailBox, string $folder)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 20);
        [$status, $message, $data, $status_code] = $this->imapService->getEmails($mailBox, $folder, $page, $perPage);
    }

    public function get_email_details(MailBox $mailBox, string $folder, int $messageId)
    {
        [$status, $message, $data, $status_code] = $this->imapService->getEmailDetails($mailBox, $folder, $messageId);
    }

    public function move_email(MailBox $mailBox, int $messageId, string $fromFolder, string $toFolder)
    {
        [$status, $message, $data, $status_code] = $this->imapService->moveEmail($mailBox, $messageId, $fromFolder, $toFolder);
    }

    public function delete_email(MailBox $mailBox, int $messageId, string $folder)
    {
        [$status, $message, $data, $status_code] = $this->imapService->deleteEmail($mailBox, $messageId, $folder);
    }
}
