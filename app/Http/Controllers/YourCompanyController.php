<?php

namespace App\Http\Controllers;

use Auth;
use Controller;
use App\Icon;
use App\Project;
use App\TableRow;
use App\Instruction;
use App\Methodology;
use App\Http\Requests\CompanyRequest;

class YourCompanyController extends Controller
{
    protected $identifierPath = "your_company";

    public function _setData($args)
    {
        $this->record = Auth::user()->company;
        if ($this->record == null) {
            abort(404);
        }
        $this->id = $this->record->id;
    }

    public function update(CompanyRequest $request, $id = null)
    {
        return parent::_update(func_get_args());
    }

    public function editHook()
    {
        $this->customValues['projects'] = Project::where('company_id', $this->user->company_id)->pluck('name', 'id');
        $this->formButtons['save_method_statement'] = [
            'class' => [
                'button',
                'is-primary',
                'submit-meth-form',
            ],
            'name' => 'save_method_statement',
            'label' => 'Save Method Statement',
            'order' => 1,
            'value' => true,
        ];
        $this->formButtons['cancel_method_statement'] = [
            'class' => [
                'button',
            ],
            'name' => 'cancel_method_statement',
            'label' => 'Cancel Method Statement',
            'order' => 2,
            'value' => true,
            'onclick' => "cancelForm('methodology');",
        ];

        $this->customValues['methTypeList'] = config('egc.methodology_list');
        $this->customValues['methodologies'] = Methodology::where('entity', '=', 'COMPANY')
            ->where('entity_id', '=', $this->id)
            ->orderBy('list_order')
            ->get();
        $this->customValues['entityType'] = 'COMPANY';
        $this->customValues['iconSelect'] = config('egc.icons');
        $this->customValues['iconImages'] = json_encode(config('egc.icon_images'));
        $this->customValues['company'] = $this->record;
        $this->customValues['whoList'] = config('egc.hazard_who_risk');
        $methodologyIds = $this->customValues['methodologies']->pluck('id');

        $this->customValues['tableRows'] = [];
        $tableRows = TableRow::whereIn('methodology_id', $methodologyIds)->orderBy('list_order')->get();
        foreach ($tableRows as $row) {
            $this->customValues['tableRows'][$row->methodology_id][] = $row;
        }

        $this->customValues['processes'] = [];
        $instructions = Instruction::whereIn('methodology_id', $methodologyIds)->orderBy('list_order')->get();
        foreach ($instructions as $instruction) {
            $this->customValues['processes'][$instruction->methodology_id][] = $instruction;
        }

        $this->customValues['icons'] = [];
        $icons = Icon::whereIn('methodology_id', $methodologyIds)->orderBy('list_order')->get();
        foreach ($icons as $icon) {
            $this->customValues['icons'][$icon->methodology_id][$icon->type][] = $icon;
        }
    }

    public function postEditHook()
    {
        $this->formButtons['cancel']['onclick'] = 'return confirm("Are you sure you want to cancel? Any unsaved changes will be lost")';
    }

    public function updated($company, $orig, $request)
    {
        $this->setupColour($company->primary_colour, $company->light_text, $company->id, $company->secondary_colour);
        if (isset($request['timescale_update']) && $request['timescale_update'] != "forward") {
            $this->overrideTimescales($company->id, $request);
        }
    }

    protected function setupColour($primary, $light, $id, $secondary = null)
    {
        if (!is_null($primary)) {
            $template = base_path('/resources/assets/css/override_template.css');
            $lightTemplate = base_path('/resources/assets/css/override_light.css');
            if (file_exists($template)) {
                $colours = file_get_contents($template);
                $newColours = str_replace(['%COLOUR%', '%SEC_COLOUR%'], [$primary, $secondary], $colours);
                if ($light && file_exists($lightTemplate)) {
                    $newColours .= file_get_contents($lightTemplate);
                }
                return file_put_contents(public_path('/css/company/'.$id.'_colour.css'), $newColours);
            }
        }
    }

    public function overrideTimescales($companyId, $request)
    {
        if ($request['timescale_update'] == "all") {
            Project::where('company_id', $companyId)
                   ->update(['review_timescale' => $request['review_timescale']]);
        } else if ($request['timescale_update'] == "select") {
            $projectIds = [];
            foreach ($request['projects_to_update'] as $key => $value) {
                if ($value == "1") {
                    $projectIds[] = $key;
                }
            }

            Project::where('company_id', $companyId)
                   ->whereIn('id', $projectIds)
                   ->update(['review_timescale' => $request['review_timescale']]);
        }
    }
}
