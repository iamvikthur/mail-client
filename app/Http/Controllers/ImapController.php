<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImapRequest;
use App\Models\MailBox;
use App\Services\ImapService;
use Illuminate\Http\Request;

class ImapController extends Controller
{
    public function __construct(private ImapService $imapService) {}
    public function index(MailBox $mailBox)
    {
        [$status, $message, $data, $status_code] = $this->imapService->getFolders($mailBox);

        return send_response($status, $data, $message, $status_code);
    }

    public function create_folder(ImapRequest $imapRequest, MailBox $mailBox)
    {
        $name = $imapRequest->validated()['folder_name'];
        [$status, $message, $data, $status_code] = $this->imapService->createFolder($mailBox, $name);

        return send_response($status, $data, $message, $status_code);
    }

    public function get_mails(Request $request, MailBox $mailBox, string $folder)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 20);
        [$status, $message, $data, $status_code] = $this->imapService->getEmails($mailBox, $folder, $page, $perPage);

        return send_response($status, $data, $message, $status_code);
    }

    public function get_email_details(MailBox $mailBox, string $folder, int $messageId)
    {
        [$status, $message, $data, $status_code] = $this->imapService->getEmailDetails($mailBox, $folder, $messageId);

        return send_response($status, $data, $message, $status_code);
    }

    public function move_email(MailBox $mailBox, int $messageId, string $fromFolder, string $toFolderPath)
    {
        [$status, $message, $data, $status_code] = $this->imapService->moveEmail($mailBox, $messageId, $fromFolder, $toFolderPath);

        return send_response($status, $data, $message, $status_code);
    }

    public function delete_email(MailBox $mailBox, int $messageId, string $folder)
    {
        [$status, $message, $data, $status_code] = $this->imapService->deleteEmail($mailBox, $messageId, $folder);

        return send_response($status, $data, $message, $status_code);
    }

    public function test_connection(MailBox $mailBox)
    {
        [$status, $message, $data, $status_code] = $this->imapService->testConnection($mailBox);

        return send_response($status, $data, $message, $status_code);
    }

    public function delete_folder(MailBox $mailBox, $folderPath)
    {
        [$status, $message, $data, $status_code] = $this->imapService->deleteAFolder($mailBox, $folderPath);

        return send_response($status, $data, $message, $status_code);
    }
}
