<?php

namespace App\Services;

use App\Models\Template;

class TemplateService extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create_template(array $data)
    {
        $template = $this->user->templates()->create($data);

        return [true, MCH_model_created("Template"), [$template], 200];
    }

    public function show_all_templates()
    {
        $templates = $this->user->templates()->get();

        return [true, MCH_model_retrieved("Templates"), $templates, 200];
    }

    public function update_template(Template $template, array $data)
    {
        $updatedTemplate = $template->update($data);

        return [true, MCH_model_updated("Template"), $updatedTemplate, 200];
    }

    public function delete_template(Template $template)
    {
        $template->delete();

        return [true, MCH_model_deleted("Template"), [], 200];
    }
}
