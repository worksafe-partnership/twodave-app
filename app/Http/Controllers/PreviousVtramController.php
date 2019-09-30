<?php

namespace App\Http\Controllers;

use Controller;
use App\Vtram;
use App\Project;
use App\Http\Requests\VtramRequest;

class PreviousVtramController extends CompanyPreviousVtramController
{
    protected $identifierPath = 'project.vtram.previous';

    public function __construct()
    {
        $this->disableCreate = true;
        $this->disableEdit = true;
        $this->disableDelete = true;
        $this->disableRestore = true;
        $this->disablePermanetlyDelete = true;
        parent::__construct();
    }

    public function viewHook()
    {
        $approvalConfig = config('structure.project.vtram.previous.approval.config');
        $this->actionButtons['approval'] = [
            'label' => ucfirst($this->pageType)." ".$approvalConfig['plural'],
            'path' => '/project/'.$this->args[0].'/vtram/'.$this->parentId.'/previous/'.$this->id.'/approval',
            'icon' => $approvalConfig['icon'],
            'order' => '500',
            'id' => 'approvalList'
        ];
    }

    public function viewEditHook()
    {
        // no withTrashed() - hide from lower level users when soft deleted.
        $this->record = Vtram::findOrFail(end($this->args));
    }

    public function indexHook()
    {
        if ($this->user->company_id !== null) {
            $vtram = Vtram::with('project')
                ->findOrFail($this->parentId);
            if ($this->user->company_id !== $vtram->project->company_id) {
                abort(404);
            }
        }
        $company = $this->user->company;
        $this->config['singular'] = $company->vtrams_name ?? 'VTRAMS';
        $this->config['plural'] = $company->vtrams_name ?? 'VTRAMS';
        $this->structure['config']['singular'] = $company->vtrams_name ?? 'VTRAMS';
        $this->structure['config']['plural'] = $company->vtrams_name ?? 'VTRAMS';
    }

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
        $company = $this->user->company;
        $this->config['singular'] = $company->vtrams_name ?? 'VTRAMS';
        $this->config['plural'] = $company->vtrams_name ?? 'VTRAMS';
        $this->structure['config']['singular'] = $company->vtrams_name ?? 'VTRAMS';
        $this->structure['config']['plural'] = $company->vtrams_name ?? 'VTRAMS';
    }

    public function viewA3($projectId, $parentVtramId, $vtramId = null, $otherId = null)
    {
        return parent::viewA3(null, $projectId, $parentVtramId, $vtramId);
    }

    public function viewA4($projectId, $parentVtramId, $vtramId = null, $otherId = null)
    {
        return parent::viewA4(null, $projectId, $parentVtramId, $vtramId);
    }
}
