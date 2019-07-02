<?php

namespace App\Http\Controllers;

use Controller;
use App\Vtram;
use App\Project;
use App\Http\Requests\VtramRequest;

class VtramController extends Controller
{
    protected $identifierPath = 'company.project.vtram';

    public function bladeHook()
    {
        $project = Project::findOrFail($this->parentId);
        $this->customValues['projects'] = Project::where('company_id', '=' , $project->company_id)
            ->pluck('name', 'id');
    }

    public function viewHook()
    {
        $prevConfig = config('structure.company.project.vtram.previous.config');
        $this->actionButtons['previous'] = [
            'label' => ucfirst($this->pageType)." ".$prevConfig['plural'],
            'path' => '/company/'.$this->args[0].'/project/'.$this->parentId.'/vtram/'.$this->id.'/previous',
            'icon' => $prevConfig['icon'],
            'order' => '500',
            'id' => 'previousList'
        ];
        $approvalConfig = config('structure.company.project.vtram.approval.config');
        $this->actionButtons['approval'] = [
            'label' => ucfirst($this->pageType)." ".$approvalConfig['plural'],
            'path' => '/company/'.$this->args[0].'/project/'.$this->parentId.'/vtram/'.$this->id.'/approval',
            'icon' => $approvalConfig['icon'],
            'order' => '500',
            'id' => 'approvalList'
        ];
    }

    public function store(VtramRequest $request, $companyId, $projectId)
    {
        $request->merge([
            'company_id' => $companyId,
        ]);
        return parent::_store(func_get_args());
    }

    public function update(VtramRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
