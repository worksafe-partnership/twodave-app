<?php

namespace App\Http\Controllers;

use Auth;
use Controller;
use App\Vtram;
use App\Project;
use App\Http\Requests\VtramRequest;

class VtramController extends CompanyVtramController
{
    protected $identifierPath = 'project.vtram';

    public function bladeHook()
    {
        if ($this->user->company_id !== null && $this->record !== null) {
            if ($this->user->company_id !== $this->record->project->company_id) {
                abort(404);
            }
        }
        if ($this->user->inRole('supervisor') && $this->record !== null) {
            if (!$this->record->project->userOnProject($this->user->id)) {
                abort(404);
            }        
        }
    }

    public function indexHook()
    {
        if ($this->user->company_id !== null) {
            $project = Project::findOrFail($this->parentId);
            if ($this->user->company_id !== $project->company_id) {
                abort(404);
            }
        }
    }

    public function viewHook()
    {
        if (can('edit', $this->identifierPath)) {
            $this->actionButtons['methodologies'] = [
                'label' => 'Edit Hazards & Methodologies',
                'path' => '/project/'.$this->parentId.'/vtram/'.$this->id.'/methodology',
                'icon' => 'receipt',
                'order' => '300',
                'id' => 'methodologyEdit',
            ];
        }
        if (can('view', $this->identifierPath)) {
            $prevConfig = config('structure.project.vtram.previous.config');
            $this->actionButtons['previous'] = [
                'label' => ucfirst($this->pageType)." ".$prevConfig['plural'],
                'path' => '/project/'.$this->parentId.'/vtram/'.$this->id.'/previous',
                'icon' => $prevConfig['icon'],
                'order' => '500',
                'id' => 'previousList'
            ];
        }
        $approvalConfig = config('structure.project.vtram.approval.config');
        $this->actionButtons['approval'] = [
            'label' => ucfirst($this->pageType)." ".$approvalConfig['plural'],
            'path' => '/project/'.$this->parentId.'/vtram/'.$this->id.'/approval',
            'icon' => $approvalConfig['icon'],
            'order' => '500',
            'id' => 'approvalList'
        ];
    }

    public function store(VtramRequest $request, $projectId, $otherId = null)
    {
        $request->merge([
            'project_id' => $projectId,
            'company_id' => Auth::user()->company_id,
        ]);
        return parent::_store([
            $request,
            $projectId
        ]);
    }

    public function update(VtramRequest $request)
    {
        return parent::_update(func_get_args());
    }

    public function editContent($projectId, $vtramId, $otherId = null)
    {
        $this->view = 'modules.company.project.vtram.editVtram';
        return parent::_custom();
    }
}
