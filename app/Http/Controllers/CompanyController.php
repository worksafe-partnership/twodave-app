<?php

namespace App\Http\Controllers;

use Storage;
use Controller;
// use File;
use EGFiles;
use App\Company;
use App\Project;
use App\Http\Requests\CompanyRequest;

class CompanyController extends Controller
{
    protected $identifierPath = 'company';

    public function editHook()
    {
        $this->customValues['projects'] = Project::where('company_id', $this->id)->pluck('name', 'id');
        $this->formButtons['back_to_edit'] = [
            'class' => [
                'submitbutton',
                'button',
                'is-primary',
            ],
            'name' => 'back_to_edit',
            'label' => 'Save',
            'order' => 150,
            'value' => true,
        ];
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
        $newCompany = $company->replicate();
        $newCompany->save();
        toast()->success('Company Cloned!', 'You\'re now editing the new Company');
        return redirect('/company/'.$newCompany->id.'/edit');
    }
}