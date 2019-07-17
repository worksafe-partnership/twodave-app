<?php

namespace App\Http\Controllers;

use Auth;
use EGFiles;
use Controller;
use App\Vtram;
use App\Hazard;
use App\Project;
use App\Company;
use App\Template;
use App\Approval;
use App\NextNumber;
use App\Methodology;
use App\Http\Classes\VTLogic;
use App\Http\Requests\VtramRequest;

class CompanyVtramController extends Controller
{
    protected $identifierPath = 'company.project.vtram';

    public function postIndexHook()
    {
        $this->customValues['templates'] = Template::where('company_id', $this->args[0])->pluck('name', 'id');
        if (isset($this->actionButtons['create']['class'])) {
            $this->actionButtons['create']['class'] .= " create_vtram";
        }
        $this->customValues['path'] = 'vtram/create';
    }

    public function postBladeHook()
    {
        if (isset($this->actionButtons['create']['class'])) {
            $this->actionButtons['create']['class'] .= " create_vtram";
        }
    }

    public function bladeHook()
    {
        $this->customValues['templates'] = Template::where('company_id', $this->args[0])->pluck('name', 'id');
    }

    public function createHook()
    {
        if (isset($_GET['template'])) {
            $template = Template::findOrFail($_GET['template']);
            if ($template->company_id != $this->args[0]) {
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

        $company = Company::findOrFail($this->args[0]);
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

    public function postEditHook()
    {
        if (in_array($this->record->status, ['REJECTED','EXTERNAL_REJECT','NEW'])) {
            $this->formButtons['save_and_submit'] = [
                'class' => [
                    'submitbutton',
                    'button',
                    'is-primary',
                ],
                'name' => 'send_for_approval',
                'label' => 'Update and Submit for Approval',
                'order' => 300,
                'value' => true,
            ];
        }
        $company = $this->args[0];
        $project = $this->args[1];
        $this->customValues['path'] = '/company/'.$company.'/project/'.$project.'/vtram/create';
    }

    public function viewHook()
    {
        $this->actionButtons['methodologies'] = [
            'label' => 'Edit Hazards & Methodologies',
            'path' => '/company/'.$this->args[0].'/project/'.$this->parentId.'/vtram/'.$this->id.'/methodology',
            'icon' => 'receipt',
            'order' => '500',
            'id' => 'methodologyEdit',
        ];
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

        $this->customValues['comments'] = VTLogic::getComments($this->record->id, $this->record->status, "VTRAM");
    }

    public function postViewHook()
    {
        // Setup Actions
        if ($this->record->pages_in_pdf == 4) {
            $this->pillButtons['view_pdf_a3'] = [
                'label' => 'View PDF A3',
                'path' => '',//$this->record->id.'/view_a3',
                'icon' => 'file-pdf',
                'order' => 100,
                'id' => 'view_pdf_a3',
                'target' => '_blank',
            ];
            $this->pillButtons['print_pdf_a3'] = [
                'label' => 'Print PDF A3',
                'path' => '',//"javascript: var wnd = window.open('".$this->record->id."/view_a3', '_blank');wnd.print();",
                'icon' => 'print',
                'order' => 100,
                'id' => 'print_pdf_a3',
                'target' => '_blank',
            ];
        }

        $this->pillButtons['view_pdf'] = [
            'label' => 'View PDF',
            'path' => '/image/'.$this->record->pdf,
            'icon' => 'file-pdf',
            'order' => 100,
            'id' => 'view_pdf',
            'target' => '_blank',
        ];
        $this->pillButtons['print_pdf'] = [
            'label' => 'Print PDF',
            'path' => "javascript:var wnd = window.open('/image/".$this->record->pdf."', '_blank');wnd.print();",
            'icon' => 'print',
            'order' => 100,
            'id' => 'print_pdf',
            'target' => '_blank',
        ];
        if (in_array($this->record->status, ['NEW','REJECTED','EXTERNAL_REJECT'])) {
            $this->pillButtons['submit_for_approval'] = [
                'label' => 'Submit for Approval',
                'path' => $this->record->id.'/submit',
                'icon' => 'tick',
                'order' => 100,
                'id' => 'submit_for_approval',
            ];
        }
        if ($this->record->status != "NEW") {
            $this->pillButtons['view_comments'] = [
                'label' => 'View All Comments',
                'path' => $this->record->id.'/comment',
                'icon' => 'comment',
                'order' => 100,
                'id' => 'view_comments',
            ];
        }

        if (strpos($this->identifierPath, 'previous') === false) {
            if ($this->record->created_from_entity == 'TEMPLATE' && $this->record->created_from_id != null) {
                $this->pillButtons['view_created_from'] = [
                    'label' => 'View Template',
                    'path' => '/template/'.$this->record->created_from_id,
                    'icon' => 'call_missed',
                    'order' => 100,
                    'id' => 'view_created_from',
                ];
            } else if ($this->record->created_from_entity == 'VTRAMS' && $this->record->created_from_id != null) {
                $this->pillButtons['view_created_from'] = [
                    'label' => 'View VTRAMS Created From',
                    'path' => $this->record->id.'/previous/'.$this->record->created_from_id,
                    'icon' => 'call_missed',
                    'order' => 100,
                    'id' => 'view_created_from',
                ];
            }
        }
    }

    public function submitForApproval($companyId, $projectId, $vtramId)
    {
        $user = Auth::user();
        $vtram = Vtram::findOrFail($vtramId);
        if ($user->company_id !== null) {
            if ($user->company_id !== $vtram->company_id) {
                abort(404);
            }
        }
        VTLogic::submitForApproval($vtram);
        if ($this->identifierPath == 'company.project.vtram') {
            $url = '/company/'.$companyId.'/project/'.$projectId.'/vtram/'.$vtramId;
        } else {
            $url = '/project/'.$projectId.'/vtram/'.$vtramId;
        }
        toast()->success("Template submitted for Approval");
        return redirect($url);
    }

    public function viewA3($companyId, $projectId, $vtramId, $otherId = null)
    {
        $user = Auth::user();
        if ($companyId == null) {
            $companyId = $user->companyId;
        }
        $vtram = Vtram::findOrFail($vtramId);
        if ($user->company_id !== null) {
            if ($user->company_id !== $vtram->company_id) {
                abort(404);
            }
        }
        return VTLogic::createA3Pdf($vtram);
    }

    public function store(VtramRequest $request, $companyId, $projectId)
    {
        $request->merge([
            'project_id' => $projectId,
            'company_id' => $companyId,
            'created_by' => Auth::id(),
        ]);
        return parent::_store(func_get_args());
    }

    public function update(VtramRequest $request)
    {
        $request->merge([
            'updated_by' => Auth::id(),
        ]);
        return parent::_update(func_get_args());
    }

    public function editContent($companyId, $projectId, $vtramId)
    {
        $company = Company::findOrFail($companyId);
        $this->view = 'modules.company.project.vtram.editVtram';
        $this->parentId = $vtramId;
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

    public function created($insert, $request, $args)
    {
        $nextNumber = NextNumber::where('company_id', '=', $insert->company_id)
            ->first();
        if (is_null($nextNumber)) {
            $nextNumber = NextNumber::create([
                'company_id' => $insert->company_id,
                'number' => 1,
            ]);
        }
        $insert->update([
           'number' => $nextNumber->number,
        ]);
        $nextNumber->increment('number');
    }

    public function updated($update, $orig, $request, $args)
    {
        if (isset($request['send_for_approval'])) {
            VTLogic::submitForApproval($update);
            toast()->success("VTRAM submitted for Approval");
        }
    }
}
