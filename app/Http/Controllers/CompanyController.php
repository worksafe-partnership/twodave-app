<?php

namespace App\Http\Controllers;

use DB;
use Storage;
use Controller;
// use File;
use EGFiles;
use App\Icon;
use App\Company;
use App\Project;
use App\TableRow;
use App\Instruction;
use App\Methodology;
use App\ProjectSubcontractor;
use App\Http\Requests\CompanyRequest;

class CompanyController extends Controller
{
    protected $identifierPath = 'company';

    public function editHook()
    {
        $this->customValues['projects'] = Project::where('company_id', $this->id)->pluck('name', 'id');
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
        $this->formButtons['back_to_edit'] = [
            'class' => [
                'submitbutton',
                'button',
                'is-primary',
            ],
            'name' => 'back_to_edit',
            'label' => 'Save & Continue',
            'order' => 150,
            'value' => true,
        ];
        $this->submitButtonText = 'Save & Exit';
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

    public function viewHook()
    {
        $projectConfig = config('structure.company.project.config');
        $this->actionButtons['projects'] = [
            'label' => ucfirst($this->pageType)." ".$projectConfig['plural'],
            'path' => '/company/'.$this->id.'/project',
            'icon' => $projectConfig['icon'],
            'order' => '550',
            'id' => 'projectsList'
        ];

        $templateConfig = config('structure.template.config');
        $this->actionButtons['templates'] = [
            'label' => ucfirst($this->pageType)." ".$templateConfig['plural'],
            'path' => '/company/'.$this->id.'/template',
            'icon' => $templateConfig['icon'],
            'order' => '560',
            'id' => 'templatesList'
        ];

        $userConfig = config('structure.company.user.config');
        $this->actionButtons['users'] = [
            'label' => ucfirst($this->pageType)." ".$userConfig['plural'],
            'path' => '/company/'.$this->id.'/user',
            'icon' => $userConfig['icon'],
            'order' => '570',
            'id' => 'templatesList'
        ];

        if (is_null($this->record['deleted_at'])) {
            $this->actionButtons['clone'] = [
                'label' => 'Clone',
                'path' => '/company/'.$this->id.'/clone',
                'icon' => 'copy',
                'order' => '600',
                'id' => 'cloneCompany',
            ];
        }
    }

    public function store(CompanyRequest $request)
    {
        return parent::_store(func_get_args());
    }

    public function update(CompanyRequest $request)
    {
        return parent::_update(func_get_args());
    }

    public function created($company, $request)
    {
        $this->setupColour($company->primary_colour, $company->light_text, $company->id);
    }

    public function updated($company, $orig, $request)
    {
        $this->setupColour($company->primary_colour, $company->light_text, $company->id);
        if (isset($request['timescale_update']) && $request['timescale_update'] != "forward") {
            $this->overrideTimescales($company->id, $request);
        }
        if (isset($request['back_to_edit'])) {
            return $this->fullPath.'/edit';
        }
        if (!$company->is_principal_contractor) {
            ProjectSubcontractor::join('projects', 'projects.id', '=', 'project_subcontractors.project_id')
                ->where('contractor_or_sub', '=', 'CONTRACTOR')
                ->where('projects.company_id', '=', $company->id)
                ->delete();            
        }
    }

    protected function setupColour($primary, $light, $id)
    {
        if (!is_null($primary)) {
            $template = base_path('/resources/assets/css/override_template.css');
            $lightTemplate = base_path('/resources/assets/css/override_light.css');
            if (file_exists($template)) {
                $colours = file_get_contents($template);
                $newColours = str_replace(['%COLOUR%'], [$primary], $colours);
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

    public function clone($companyId)
    {
        $company = Company::findOrFail($companyId);
        $newCompany = DB::transaction(function () use ($company) {
            $newCompany = $company->replicate();
            $newCompany->logo = null;
            $newCompany->save();
            $insertIcons = [];
            $insertTableRows = [];
            $insertInstructions = [];
            $insertHazMethLink = [];
            foreach ($company->methodologies as $methodology) {
                $newMeth = $methodology->toArray();
                unset($newMeth['id']);
                $newMeth['entity_id'] = $newCompany->id;
                $meth = Methodology::create($newMeth);
                foreach ($methodology->icons as $icon) {
                    $newIcon = $icon->toArray();
                    unset($newIcon['id']);
                    $newIcon['methodology_id'] = $meth->id;
                    $insertIcons[] = $newIcon;
                }
                foreach ($methodology->instructions as $instruction) {
                    $newInstruction = $instruction->toArray();
                    unset($newInstruction['id']);
                    $newInstruction['methodology_id'] = $meth->id;
                    $insertInstructions[] = $newInstruction;
                }
                foreach ($methodology->tableRows as $row) {
                    $newRow = $row->toArray();
                    unset($newRow['id']);
                    $newRow['methodology_id'] = $meth->id;
                    $insertTableRows[] = $newRow;
                }
            }
            Icon::insert($insertIcons);
            TableRow::insert($insertTableRows);
            Instruction::insert($insertInstructions);
            return $newCompany;
        });
        toast()->success('Company Cloned!', 'You\'re now editing the new Company');
        return redirect('/company/'.$newCompany->id.'/edit');
    }
}
