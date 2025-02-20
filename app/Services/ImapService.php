<?php

namespace App\Services;

use App\Models\MailBox;
use Illuminate\Support\Facades\Log;
use Webklex\IMAP\Facades\Client;

class ImapService
{
    public function connect(MailBox $mailBox)
    {
        $client = Client::make([
            'host'          => $mailBox->imap_host,
            'port'          => $mailBox->imap_port,
            'encryption'    => $mailBox->imap_encryption,
            'validate_cert' => true,
            'username'      => $mailBox->imap_username,
            'password'      => $mailBox->imap_password,
            'protocol'      => 'imap'
        ]);
        $client->connect();
        return $client;
    }

    public function getFolders(MailBox $mailBox)
    {
        try {
            $client = $this->connect($mailBox);
            $folders = $client->getFolders();
            $client->disconnect();
            $categorizedFolders =  $this->categorizeFolders($folders);
            return [true, "", $categorizedFolders, 200];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [true, "", [], 500];
            //throw $th;
        }
    }

    public function getEmails(MailBox $mailBox, string $folderName, int $page = 1, int $perPage = 20)
    {
        try {
            $client = $this->connect($mailBox);
            $folder = $client->getFolder($folderName);
            $messages = $folder->messages()->all()->limit($perPage, ($page - 1) * $perPage)->get();
            $client->disconnect();
            $messagesCollection = $messages->map(function ($message) {
                return [
                    'uid' => $message->getUid(),
                    'subject' => $message->getSubject(),
                    'from' => $message->getFrom()[0]->mail,
                    'date' => $message->getDate(),
                ];
            })->toArray();
            return [true, "", $messagesCollection, 200];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [true, "", [], 500];
            //throw $th;
        }
    }

    public function getEmailDetails(MailBox $mailBox, string $folderName, int $messageId)
    {
        try {
            $client = $this->connect($mailBox);
            $folder = $client->getFolder($folderName);
            $message = $folder->messages()->getMessageByUid($messageId);
            $client->disconnect();
            $emailDetails = [
                'uid' => $message->getUid(),
                'subject' => $message->getSubject(),
                'from' => $message->getFrom()[0]->mail,
                'to' => $message->getTo()[0]->mail,
                'date' => $message->getDate(),
                'body' => $message->getHTMLBody() ?: $message->getTextBody(),
            ];
            return [true, "", $emailDetails, 200];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [true, "", [], 500];
            //throw $th;
        }
    }

    public function moveEmail(MailBox $mailBox, int $messageId, string $fromFolder, string $toFolder)
    {
        try {
            $client = $this->connect($mailBox);
            $folder = $client->getFolder($fromFolder);
            $message = $folder->messages()->getMessageByUid($messageId);
            $message->move($toFolder);
            $client->disconnect();

            return [true, "", [], 200];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [true, "", [], 500];
            //throw $th;
        }
    }

    public function deleteEmail(MailBox $mailBox, int $messageId, string $folderName)
    {
        try {
            $client = $this->connect($mailBox);
            $folder = $client->getFolder($folderName);
            $message = $folder->messages()->getMessageByUid($messageId);
            $message->delete();
            $client->disconnect();

            return [true, "", [], 200];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [true, "", [], 500];
            //throw $th;
        }
    }

    public function createFolder(MailBox $mailBox, string $folderName)
    {
        try {
            $client = $this->connect($mailBox);
            $client->createFolder($folderName);
            $client->disconnect();

            return [true, "", [], 200];
        } catch (\Throwable $th) {
            return [false, "", [], 500];
        }
    }

    private function categorizeFolders($folders)
    {
        $categorized = [];
        foreach ($folders as $folder) {
            $category = $this->determineCategory($folder);
            $categorized[] = [
                'name' => $folder->name,
                'path' => $folder->path,
                'category' => $category,
            ];
        }
        return $categorized;
    }

    private function determineCategory($folder)
    {
        $name = strtolower($folder->name);
        if ($name === 'inbox') return 'inbox';
        if (strpos($name, 'spam') !== false || strpos($name, 'junk') !== false) return 'spam';
        if (strpos($name, 'sent') !== false) return 'sent';
        if (strpos($name, 'drafts') !== false) return 'drafts';
        if (strpos($name, 'trash') !== false) return 'trash';
        return 'other';
    }
}
