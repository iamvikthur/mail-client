<?php

namespace App\Services;

use App\Models\MailBox;
use Illuminate\Support\Facades\Log;
use Webklex\IMAP\Facades\Client;

class ImapService
{
    public function connect(MailBox $mailBox)
    {
        $smtpDetails = $mailBox->imap_details();
        $smtpDetails['validate_cert'] = true;
        $smtpDetails['protocol'] = 'imap';

        $client = Client::make($smtpDetails);
        $client->connect();

        return $client;
    }

    public function getFolders(MailBox $mailBox)
    {
        try {
            $client = $this->connect($mailBox);
            $folders = $client->getFolders();
            $client->disconnect();
            // Log::info("FOLDERS", $folders->toArray());
            $categorizedFolders =  $this->categorizeFolders($folders);
            return [true, MCH_model_retrieved("Folders"), $categorizedFolders, 200];
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
            // Log::info("MESSAGES", $folder->messages()->all()->limit($perPage, ($page - 1) * $perPage)->get()->toArray());
            $messages = $folder->messages()->all()->limit($perPage, ($page - 1) * $perPage)->get();

            $client->disconnect();
            $messagesCollection = $messages->map(function ($message) {
                return [
                    'uid' => $message->getUid(),
                    'subject' => $this->decodeSubject($message->getSubject()),
                    'body' => $message->getHTMLBody() ?: $message->getTextBody(),
                    'from' => $message->getFrom()[0]->mail ?? 'Unknown',
                    'date' => $message->getDate() instanceof \Carbon\Carbon
                        ? $message->getDate()->format('Y-m-d H:i:s')
                        : (string) $message->getDate(),
                ];
            })->toArray();
            return [true, MCH_model_retrieved("Mailbox Emails"), $messagesCollection, 200];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [true, $th->getMessage(), [], 500];
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

    public function moveEmail(MailBox $mailBox, int $messageId, string $fromFolder, string $toFolderPath)
    {
        try {
            $client = $this->connect($mailBox);
            $folder = $client->getFolder($fromFolder);
            $message = $folder->messages()->getMessageByUid($messageId);

            $message->move($toFolderPath);

            $client->disconnect();

            return [true, "Email moved successfully", [], 200];
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [$th]);
            return [false, $th->getMessage(), [], 500];
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

            return [true, "Email deleted", [], 200];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return [false, $th->getMessage(), [], 500];
            //throw $th;
        }
    }

    public function createFolder(MailBox $mailBox, string $folderName)
    {
        try {
            $client = $this->connect($mailBox);

            $client->createFolder($folderName);

            $client->disconnect();

            return [true, MCH_model_created("Folder"), [], 200];
        } catch (\Throwable $th) {
            $msg = $th->getMessage() === "BAD No mailbox selected (0.001 + 0.000 secs). )"
                ? "Folder has been created, please check your folders"
                : $th->getMessage();

            return [false, $msg, [], 500];
        }
    }

    public function testConnection(MailBox $mailBox)
    {
        try {
            $this->connect($mailBox);
            return [true, "Connection test successful", [$mailBox], 200];
        } catch (\Throwable $th) {
            Log::info($th->getMessage(), [$th]);
            return [false, $th->getMessage(), [$mailBox], 400];
            //throw $th;
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
                'has_children' => $folder->has_children,
                'children' => $this->categorizeFolders($folder->children ?? []),
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

    private function decodeSubject($subject)
    {
        $decoded = '';
        foreach (imap_mime_header_decode($subject) as $part) {
            $decoded .= $part->text;
        }
        return mb_convert_encoding($decoded, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
    }
}
