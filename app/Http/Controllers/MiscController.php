<?php

namespace App\Http\Controllers;

use App\Enums\CampaignStatusEnum;
use Illuminate\Http\Request;

class MiscController extends Controller
{
    public function get_campaign_statuses()
    {
        $statuses = array_column(CampaignStatusEnum::cases(), "value");

        return send_response(true, $statuses, MCH_enumRetrieved("Campaign status"), 200);
    }
}
