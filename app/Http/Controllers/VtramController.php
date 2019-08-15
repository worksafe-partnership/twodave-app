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
        $company = Company::findOrFail($this->user->company_id);
        if (isset($_GET['template'])) {
            $template = Template::findOrFail($_GET['template']);
            $this->customValues['createdFromEntity'] = "TEMPLATE";
            $this->customValues['createdFromId'] = $_GET['template'];
            if ($template->company_id != $this->user->company_id) {
                abort(404);
            }
            if ($template->status != "CURRENT") {
                abort(404);
            }

            $this->customValues['name'] = $template['name'];
            $this->customValues['reference'] = $template['reference'];
            $this->customValues['logo'] = $template['logo'];

            $this->customValues['main_description'] = $template['main_description'];
            $this->customValues['post_risk_assessment_text'] = $template['post_risk_assessment_text'];
            $this->customValues['task_description'] = $template['task_description'];
            $this->customValues['plant_and_equipment'] = $template['plant_and_equipment'];
            $this->customValues['disposing_of_waste'] = $template['disposing_of_waste'];
            $this->customValues['first_aid'] = $template['first_aid'];
            $this->customValues['noise'] = $template['noise'];
            $this->customValues['working_at_height'] = $template['working_at_height'];
            $this->customValues['manual_handling'] = $template['manual_handling'];
            $this->customValues['accident_reporting'] = $template['accident_reporting'];
        } else {
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

    }

    public function postIndexHook()
    {
        if (isset($this->actionButtons['create']['class'])) {
            $this->actionButtons['create']['class'] .= " create_vtram";
        }
        $this->customValues['templates'] = Template::where('company_id', $this->user->company_id)
                                                   ->where('status', 'CURRENT')
                                                   ->pluck('name', 'id');
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
        $this->customValues['templates'] = Template::where('company_id', $this->user->company_id)
                                                   ->where('status', 'CURRENT')
                                                   ->pluck('name', 'id');
        $this->customValues['path'] = 'create';
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
        if (can('edit', $this->identifierPath) && in_array($this->record->status, ['NEW','EXTERNAL_REJECT','REJECTED'])) {
            $this->actionButtons['methodologies'] = [
                'label' => 'Hazards & Methodologies',
                'path' => '/project/'.$this->parentId.'/vtram/'.$this->id.'/methodology',
                'icon' => 'receipt',
                'order' => '500',
                'id' => 'methodologyEdit',
            ];
        }
        if (can('edit', $this->identifierPath)) {
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
        if (!in_array($this->record->status, ['NEW','EXTERNAL_REJECT','REJECTED'])) {
            $this->disableEdit = true;
        }
    }

    public function postEditHook()
    {
        $project = $this->args[0];
        if (in_array($this->record->status, ['REJECTED','EXTERNAL_REJECT','NEW'])) {
            $this->formButtons['save_and_submit'] = [
                'class' => [
                    'submitbutton',
                    'button',
                    'is-primary',
                ],
                'name' => 'send_for_approval',
                'label' => 'Update and Submit for Approval',
                'order' => 150,
                'value' => true,
            ];
        }

        // reorder the formButtons as EGL doesn't appear to do this for us.
        usort($this->formButtons, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });

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

    public function viewA4($projectId, $vtramId, $otherId = null, $otherId2 = null)
    {
        return parent::viewA4($otherId, $projectId, $vtramId);
    }

    public function store(VtramRequest $request, $projectId, $otherId = null)
    {
        $user = Auth::user();
        $company = Company::findOrFail($user->company_id);
        $request->merge([
            'project_id' => $projectId,
            'company_id' => $user->company_id,
            'main_description' => $company->main_description,
            'post_risk_assessment_text' => $company->post_risk_assessment_text,
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
        return parent::editContent(Auth::User()->company_id, $projectId, $vtramId);
    }
}
