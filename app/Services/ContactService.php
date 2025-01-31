<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Support\Str;
use App\Models\ContactList;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class ContactService extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create_contact(ContactList $contactList, array $data): array
    {
        if (isset($data['contact_file'])) {
            $contactFile = $data['contact_file'];
            throw_if(!($contactFile instanceof UploadedFile), new \Exception('Invalid contact file value'));

            $extension = $contactFile->getClientOriginalExtension();

            if ($extension === 'csv') {
                $data = $this->readCsvFile($contactFile);
            } elseif ($extension === 'xlsx') {
                $data = $this->readExcelFile($contactFile);
            } else {
                throw new \Exception('Unsupported file type.');
            }

            // Upload the data to the database
            $contacts = $this->uploadToDatabase($data, $contactList);

            return [true, MCH_model_created("Contact"), $contacts, 200];
        }

        $contacts = $contactList->contacts()->create($data);

        return [true, MCH_model_created("Contact"), $contacts, 200];
    }

    private function readCsvFile(UploadedFile $file): array
    {
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));

        // Remove the header row if present
        $header = array_shift($data);

        return $data;
    }

    private function readExcelFile(UploadedFile $file): array
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        // Remove the header row if present
        $header = array_shift($data);

        return $data;
    }

    private function uploadToDatabase(array $data, ContactList $contactList): array
    {
        $skippedRows = [];
        foreach ($data as $key => $row) {

            try {

                $firstname = $row[0] ?? "";
                $lastname = $row[1] ?? "";
                $email = $row[2] ?? "";

                $firstname = $this->sanitizeFirstOrLastName($firstname);
                $lastname = $this->sanitizeFirstOrLastName($lastname);
                $email = $this->sanitizeEmail($email);

                $this->validateData([
                    "firstname" => $firstname,
                    "lastname" => $lastname,
                    "email" => $email
                ], $key);

                $contactList->contacts()->create(
                    [
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'email' => $email,
                    ]
                );
            } catch (ValidationException $e) {
                //throw $th;
                Log::error("Validation failed for row $key. skipped");
                $skippedRows[$key] = $e->errors();
                continue;
            }
        }

        $returnData = $contactList->contacts()->get();
        $returnData->merge(["skipped_rows" => $skippedRows]);

        return $returnData->toArray();
    }

    private function sanitizeFirstOrLastName(string $firstname): string
    {
        // Remove unwanted characters and trim whitespace
        return Str::title(trim($firstname));
    }

    private function sanitizeEmail(string $email): string
    {
        // Convert email to lowercase and trim whitespace
        return Str::lower(trim($email));
    }

    private function validateData(array $data, int $rowNumber)
    {
        $rules = [
            'firstname' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'lastname' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|unique:users,email',
        ];

        $messages = [
            'firstname.required' => "The firstname field is required on row $rowNumber ...skipped",
            'firstname.string' => "The firstname must be a string on row $rowNumber ...skipped",
            'firstname.max' => "The firstname must not exceed 255 characters on row $rowNumber ...skipped",
            'firstname.regex' => "The firstname must contain only letters and spaces on row $rowNumber ...skipped",
            'email.required' => "The email field is required on row $rowNumber ...skipped",
            'email.email' => "The email must be a valid email address on row $rowNumber ...skipped",
            'email.unique' => "The email has already been taken on row $rowNumber ...skipped",
        ];

        // Validate the data
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function show_all_contacts(ContactList $contactList): array
    {
        $contactLists = $contactList->contacts;

        return [true, MCH_model_retrieved("Contacts"), $contactLists, 200];
    }

    public function update_contact(Contact $contact, array $data)
    {
        $updatedContactList = $contact->update($data);

        return [true, MCH_model_updated("Contact"), [$updatedContactList], 200];
    }

    public function delete_contact(Contact $contact)
    {
        $contact->delete();

        return [true, MCH_model_created("Contact"), [], 200];
    }
}
