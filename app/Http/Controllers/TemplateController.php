<?php

namespace App\Http\Controllers;

use Auth;
use Controller;
use App\Hazard;
use App\Company;
use App\Template;
use App\Methodology;
use Illuminate\Http\Request;
use App\Http\Classes\VTLogic;
use App\Http\Requests\TemplateRequest;

class TemplateController extends Controller
{
    protected $identifierPath = 'template';

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
    }

    public function viewHook()
    {
        $this->actionButtons['methodologies'] = [
            'label' => 'Edit Hazards & Methodologies',
            'path' => '/template/'.$this->id.'/methodology',
            'icon' => 'receipt',
            'order' => '500',
            'id' => 'methodologyEdit',
        ];

        $prevConfig = config('structure.template.previous.config');
        $this->actionButtons['previous'] = [
            'label' => ucfirst($this->pageType)." ".$prevConfig['plural'],
            'path' => '/template/'.$this->id.'/previous',
            'icon' => $prevConfig['icon'],
            'order' => '500',
            'id' => 'previousList'
        ];
        $approvalConfig = config('structure.template.approval.config');
        $this->actionButtons['approval'] = [
            'label' => ucfirst($this->pageType)." ".$approvalConfig['plural'],
            'path' => '/template/'.$this->id.'/approval',
            'icon' => $approvalConfig['icon'],
            'order' => '500',
            'id' => 'approvalList'
        ];
    }

    public function bladeHook()
    {
        $this->customValues['companies'] = Company::pluck('name', 'id');
    }

    public function store(TemplateRequest $request)
    {
        return parent::_store(func_get_args());
    }

    public function update(TemplateRequest $request, $companyId)
    {
        return parent::_update(func_get_args());
    }

    public function updateFromMethodology(Request $request)
    {
        $request->merge([
            'updated_by' => Auth::id(),
            'from_methodology' => true, // used for override in update function.
            'return_path' => str_replace("edit_extra", "methodology", $request->path())
        ]);

        return parent::_update(func_get_args());
    }

    public function editContent($templateId, $otherId = null)
    {
        $this->record = Template::findOrFail($templateId);
        $this->user = Auth::user();
        if ($this->user->company_id !== null && $this->record !== null && $this->record->company_id !== null) {
            if ($this->user->company_id !== $this->record->company_id) {
                abort(404);
            }
        }
        if (is_null($otherId)) {
            $otherId = $this->user->company_id;
        }

        $company = Company::find($otherId);
        if ($company != null) {
            $this->customValues['riskList'] = [
                0 => $company->no_risk_character,
                1 => $company->low_risk_character,
                2 => $company->med_risk_character,
                3 => $company->high_risk_character,
            ];
        } else {
            $this->customValues['riskList'] = [
                0 => '#',
                1 => 'L',
                2 => 'M',
                3 => 'H',
            ];
        }
        $this->view = 'modules.company.project.vtram.editVtram';
        $this->parentId = $templateId;
        $this->customValues['whoList'] = config('egc.hazard_who_risk');
        $this->customValues['methTypeList'] = config('egc.methodology_list');
        $this->customValues['hazards'] = Hazard::where('entity', '=', 'TEMPLATE')
            ->where('entity_id', '=', $templateId)
            ->orderBy('list_order')
            ->get();
        $this->customValues['methodologies'] = Methodology::where('entity', '=', 'TEMPLATE')
            ->where('entity_id', '=', $templateId)
            ->orderBy('list_order')
            ->get();

        $this->customValues['comments'] = VTLogic::getComments($this->record->id, $this->record->status, 'TEMPLATE');
        $this->customValues['entityType'] = 'TEMPLATE';

        return parent::_custom();
    }

    public function postViewHook()
    {
        // Setup Actions
        if ($this->record->pages_in_pdf == 4) {
            $this->pillButtons['view_pdf_a3'] = [
                'label' => 'View PDF A3',
                'path' => $this->record->id.'/view_a3',
                'icon' => 'file-pdf',
                'order' => 100,
                'id' => 'view_pdf_a3',
                'target' => '_blank',
            ];
            $this->pillButtons['print_pdf_a3'] = [
                'label' => 'Print PDF A3',
                'path' => "javascript: var wnd = window.open('".$this->record->id."/view_a3', '_blank');wnd.print();",
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

        if (strpos($this->identifierPath, 'previous') === false && $this->record->created_from != null) {
            $this->pillButtons['view_created_from'] = [
                'label' => 'View Template',
                'path' => $this->record->id.'/previous/'.$this->record->created_from,
                'icon' => 'call_missed',
                'order' => 100,
                'id' => 'view_created_from',
            ];
        }
    }

    public function submitForApproval($templateId, $otherId = null)
    {
        $user = Auth::user();
        $template = Template::findOrFail($templateId);
        if ($user->company_id !== null) {
            if ($user->company_id !== $template->company_id && $template->company_id !== null) {
                abort(404);
            }
        }
        VTLogic::submitForApproval($template);
        if ($this->identifierPath == 'company.template') {
            $url = '/company/'.$otherId.'/template/'.$templateId;
        } else {
            $url = '/template/'.$templateId;
        }
        toast()->success("Template submitted for Approval");
        return redirect($url);
    }

    public function viewA3($templateId, $companyId = null, $otherId = null)
    {
        $user = Auth::user();
        if ($companyId == null) {
            $companyId = $user->companyId;
        }
        $template = Template::findOrFail($templateId);
        if ($user->company_id !== null) {
            if ($user->company_id !== $template->company_id && $template->company_id !== null) {
                abort(404);
            }
        }
        return VTLogic::createA3Pdf($template);
    }

    public function updated($update, $orig, $request, $args)
    {
        if (isset($request['send_for_approval'])) {
            VTLogic::submitForApproval($update);
            toast()->success("Template submitted for Approval");
        }

        if (isset($request['from_methodology'])) {
            return $request['return_path'];
        }
    }

    public function created($insert, $request, $args)
    {
        VTLogic::createDefaultMethodologies($insert, "TEMPLATE");
    }
}
