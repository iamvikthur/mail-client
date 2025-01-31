<?php

namespace App\Services;

use App\Models\Campaign;

class CampaignService extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create_campaign(array $data)
    {
        $contactListIds = $data["contact_list_ids"];

        unset($data["contact_list_ids"]);

        $campaign = $this->user->campaigns()->create($data);

        $campaign->contactLists()->attach($contactListIds, [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [true, MCH_model_created("Campaign"), [$campaign], 200];
    }

    public function show_all_campaigns()
    {
        $campaigns = $this->user->campaigns()->with('contactLists')->orderBy("created_at", "desc")->get();

        return [true, MCH_model_retrieved("Campaigns"), $campaigns, 200];
    }

    public function update_campaign(Campaign $campaign, array $data)
    {
        if (isset($data["contact_list_ids"])) {
            $contactListIds = $data["contact_list_ids"];
            $campaign->contactLists()->sync($contactListIds);

            unset($data["contact_list_ids"]);
        }

        $updatedCampaign = $campaign->update($data);

        return [true, MCH_model_updated("Campaign"), [$updatedCampaign], 200];
    }

    public function delete_campaign(Campaign $campaign)
    {
        $campaign->delete();

        return [true, MCH_model_deleted("Campaign"), [], 200];
    }
}
