<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampaignRequest;
use App\Models\Campaign;
use App\Services\CampaignService;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function __construct(private CampaignService $campaignService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        [$status, $message, $data, $status_code] = $this->campaignService->show_all_campaigns();

        return send_response($status, $data, $message, $status_code);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CampaignRequest $campaignRequest)
    {
        [$status, $message, $data, $status_code] = $this->campaignService->create_campaign(
            $campaignRequest->validated()
        );

        return send_response($status, $data, $message, $status_code);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CampaignRequest $campaignRequest, Campaign $campaign)
    {
        [$status, $message, $data, $status_code] = $this->campaignService->update_campaign(
            $campaign,
            $campaignRequest->validated()
        );

        return send_response($status, $data, $message, $status_code);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        [$status, $message, $data, $status_code] = $this->campaignService->delete_campaign(
            $campaign
        );

        return send_response($status, $data, $message, $status_code);
    }
}
