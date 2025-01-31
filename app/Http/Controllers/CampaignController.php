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
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CampaignRequest $campaignRequest)
    {
        [$status, $message, $data, $status_code] = $this->campaignService->create_campaign(
            $campaignRequest->validated()
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign)
    {
        //
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        [$status, $message, $data, $status_code] = $this->campaignService->delete_campaign(
            $campaign
        );
    }
}
