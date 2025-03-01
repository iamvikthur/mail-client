<?php

namespace App\Services;

use App\Enums\CampaignStatusEnum;
use App\Models\Campaign;
use Illuminate\Support\Str;

class CampaignService extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create_campaign(array $data): array
    {
        $contactListIds = $data["contact_list_ids"];

        unset($data["contact_list_ids"]);

        $data['slug'] = $this->generateUniqueSlug($data['name'], new Campaign());

        $campaign = $this->user->campaigns()->create($data);

        $campaign->contactLists()->attach($contactListIds, [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [true, MCH_model_created("Campaign"), [$campaign], 200];
    }

    public function show_all_campaigns(): array
    {
        $campaigns = $this->user->campaigns()->with('contactLists')->orderBy("created_at", "desc")->get()->toArray();

        return [true, MCH_model_retrieved("Campaigns"), $campaigns, 200];
    }

    public function update_campaign(Campaign $campaign, array $data): array
    {
        if (isset($data["contact_list_ids"])) {
            $contactListIds = $data["contact_list_ids"];
            $campaign->contactLists()->sync($contactListIds);

            unset($data["contact_list_ids"]);
        }

        if (isset($data['status']) && $data['status'] === CampaignStatusEnum::PUBLISHED->value) {
            if ($campaign->template === null) {
                return [false, CAMPAIGN_TEMPLATE_NOT_SET, [], 400];
            }
        }

        $campaign->update($data);

        $campaign->fresh();

        $campaign->publish_campaign();

        return [true, MCH_model_updated("Campaign"), [$campaign], 200];
    }

    public function delete_campaign(Campaign $campaign): array
    {
        if ($campaign->status !== CampaignStatusEnum::DRAFT) {
            return [false, CAMPAIGN_TEMPLATE_NOT_SET, [], 400];
        }

        $campaign->delete();

        return [true, MCH_model_deleted("Campaign"), [], 200];
    }
}
