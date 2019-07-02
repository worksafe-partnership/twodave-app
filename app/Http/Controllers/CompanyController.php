<?php

namespace App\Http\Controllers;

use Controller;
use App\Company;
use App\Http\Requests\CompanyRequest;

class CompanyController extends Controller
{
    protected $identifierPath = 'company';

    public function viewHook()
    {
        $projectConfig = config('structure.company.project.config');
        $this->actionButtons['projects'] = [
            'label' => ucfirst($this->pageType)." ".$projectConfig['plural'],
            'path' => '/company/'.$this->id.'/project',
            'icon' => $projectConfig['icon'],
            'order' => '400',
            'id' => 'projectsList'
        ];

        $templateConfig = config('structure.template.config');
        $this->actionButtons['templates'] = [
            'label' => ucfirst($this->pageType)." ".$templateConfig['plural'],
            'path' => '/company/'.$this->id.'/template',
            'icon' => $templateConfig['icon'],
            'order' => '500',
            'id' => 'templatesList'
        ];
    }
    
    public function store(CompanyRequest $request)
    {
        return parent::_store(func_get_args());
    }

    public function update(CompanyRequest $request)
    {
        return parent::_update(func_get_args());
    }

    public function created($company, $args)
    {
        $this->setupColour($company->primary_colour, $company->light_text, $company->id);
        $this->setupLogo($company->logo);
    }

    public function updated($company, $orig, $args)
    {
        $this->setupColour($company->primary_colour, $company->light_text, $company->id);
        $this->setupLogo($company->logo);
    }

    protected function setupColour($primary, $light, $id)
    {
        if (!is_null($colour)) {
            $template = base_path('/resources/assets/css/override_template.css');
            $lightTemplate = base_path('/resources/assets/css/override_light.css');
            if (file_exists($template)) {
                $colours = file_get_contents($template);
                $newColours = str_replace(['%COLOUR%'], [$primary], $colours);
                if ($light && file_exists($lightTemplate)) {
                    $newColours .= file_get_contents($lightTemplate);
                }
                return file_put_contents(public_path('/css/'.$id.'_colour.css'));
            }
        }
    }

    protected function setupLogo()
    {

    }
}
