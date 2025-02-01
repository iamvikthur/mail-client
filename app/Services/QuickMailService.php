<?php

namespace App\Services;

use App\Models\QuickMail;

class QuickMailService extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create_quick_mail(array $data)
    {
        if ($this->canUseTemplate($data['template_id'])) {
            $quickMail = $this->user->quickMails()->create($data);
            return [true, MCH_model_created("Quick mail"), [$quickMail], 200];
        } else {
            throw new \Exception('You are not authorized to use this template');
        }
    }

    public function show_all_quick_mails()
    {
        $quickMails = $this->user->quickMails()->get()->toArray();
        return [true, MCH_model_retrieved("Quick mail"), $quickMails, 200];
    }

    public function update_quick_mail(QuickMail $quickMail, array $data)
    {
        if (isset($data["template_id"])) {
            if (!$this->canUseTemplate($data["template_id"])) {
                throw new \Exception('You are not authorized to use this template');
            }
        }

        $updatedQuickMail = $quickMail->update($data);

        return [true, MCH_model_updated("Quick mail"), [$updatedQuickMail], 200];
    }

    public function delete_quick_mail(QuickMail $quickMail)
    {
        $quickMail->delete();

        return [true, MCH_model_deleted("Quick mail"), [], 200];
    }
}
