<?php

namespace App\Http\Controllers;

use App\Http\Requests\TemplateRequest;
use App\Models\Template;
use App\Services\TemplateService;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function __construct(private TemplateService $templateService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        [$status, $message, $data, $status_code] = $this->templateService->show_all_templates();
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
    public function store(TemplateRequest $templateRequest)
    {
        [$status, $message, $data, $status_code] = $this->templateService->create_template(
            $templateRequest->validated()
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Template $template)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Template $template)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TemplateRequest $templateRequest, Template $template)
    {
        [$status, $message, $data, $status_code] = $this->templateService->update_template(
            $template,
            $templateRequest->validated()
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Template $template)
    {
        [$status, $message, $data, $status_code] = $this->templateService->delete_template(
            $template
        );
    }
}
