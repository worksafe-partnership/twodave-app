<?php

namespace App\Http\Controllers;

use Auth;
use Controller;
use App\Vtram;
use App\Hazard;
use App\Project;
use App\Company;
use App\Template;
use App\Methodology;
use App\Http\Classes\VTLogic;
use App\Http\Requests\VtramRequest;

class VtramController extends CompanyVtramController
{
    protected $identifierPath = 'project.vtram';

    public function createHook()
    {
        if (isset($_GET['template'])) {
            $template = Template::findOrFail($_GET['template']);
            if ($template->company_id != $this->user->company_id) {
                abort(404);
            }
            $this->customValues['name'] = $template['name'];
            $this->customValues['reference'] = $template['reference'];
            $this->customValues['logo'] = $template['logo'];
            // not sure if the page is complete, so leaving these commented out for now.
            // $this->customValues['key_points'] = $template['key_points']; //  not on blade???
            // $this->customValues['havs_noise_assessment'] = $template['havs_noise_assessment']; //  not on blade???
            // $this->customValues['coshh_assessment'] = $template['coshh_assessment']; // not on blade ??
            // $this->customValues['responsible_person'] = $template['responsible_person'];
            // $this->customValues['show_responsible_person'] = $template['show_responsible_person'];
        }

        $company = Company::findOrFail($this->user->company_id);
        $this->customValues['main_description'] = $company['main_description'];
        $this->customValues['post_risk_assessment_text'] = $company['post_risk_assessment_text'];
        $this->customValues['task_description'] = $company['task_description'];
        $this->customValues['plant_and_equipment'] = $company['plant_and_equipment'];
        $this->customValues['disposing_of_waste'] = $company['disposing_of_waste'];
        $this->customValues['first_aid'] = $company['first_aid'];
        $this->customValues['noise'] = $company['noise'];
        $this->customValues['working_at_height'] = $company['working_at_height'];
        $this->customValues['manual_handling'] = $company['manual_handling'];
        $this->customValues['accident_reporting'] = $company['accident_reporting'];
    }


    public function postIndexHook()
    {
        if (isset($this->actionButtons['create']['class'])) {
            $this->actionButtons['create']['class'] .= " create_vtram";
        }
        $this->customValues['templates'] = Template::where('company_id', $this->user->company_id)->pluck('name', 'id');
        $this->customValues['path'] = 'vtram/create';
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
        $this->customValues['templates'] = Template::where('company_id', $this->user->company_id)->pluck('name', 'id');
        $this->customValues['path'] = 'hello';
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
                'order' => '500',
                'id' => 'methodologyEdit',
            ];
        }
        if (can('view', $this->identifierPath)) {
            $prevConfig = config('structure.project.vtram.previous.config');
            $this->actionButtons['previous'] = [
                'label' => ucfirst($this->pageType)." ".$prevConfig['plural'],
                'path' => '/project/'.$this->parentId.'/vtram/'.$this->id.'/previous',
                'icon' => $prevConfig['icon'],
                'order' => '600',
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

    public function postEditHook()
    {
        $project = $this->args[0];
        $this->customValues['path'] = '/project/'.$project.'/vtram/create';
    }

    public function submitForApproval($projectId, $vtramId, $otherId = null)
    {
        return parent::submitForApproval(null, $projectId, $vtramId);
    }

    public function viewA3($projectId, $vtramId, $otherId = null, $otherId2 = null)
    {
        return parent::viewA3($otherId, $projectId, $vtramId);
    }

    public function store(VtramRequest $request, $projectId, $otherId = null)
    {
        $user = Auth::user();
        $request->merge([
            'project_id' => $projectId,
            'company_id' => $user->company_id,
            'created_by' => $user->id,
        ]);
        return parent::_store([
            $request,
            $projectId
        ]);
    }

    public function update(VtramRequest $request)
    {
        $request->merge([
            'updated_by' => Auth::id(),
        ]);
        return parent::_update(func_get_args());
    }

    public function editContent($projectId, $vtramId, $otherId = null)
    {
        $company = Company::findOrFail(Auth::User()->company_id);
        $this->view = 'modules.company.project.vtram.editVtram';
        $this->customValues['whoList'] = config('egc.hazard_who_risk');
        $this->customValues['riskList'] = [
            0 => $company->no_risk_character,
            1 => $company->low_risk_character,
            2 => $company->med_risk_character,
            3 => $company->high_risk_character,
        ];

        $this->customValues['hazards'] = Hazard::where('entity', '=', 'VTRAM')
            ->where('entity_id', '=', $vtramId)
            ->orderBy('list_order')
            ->get()
            ->toJson();
        $this->customValues['methodologies'] = Methodology::where('entity', '=', 'VTRAM')
            ->where('entity_id', '=', $vtramId)
            ->orderBy('list_order')
            ->get()
            ->toJson();

        $this->record = Vtram::findOrFail($vtramId);
        $this->customValues['comments'] = VTLogic::getComments($this->record->id, $this->record->status, "VTRAM");


        return parent::_custom();
    }
}
