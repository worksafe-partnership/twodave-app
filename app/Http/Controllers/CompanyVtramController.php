<?php

namespace App\Http\Controllers;

use Auth;
use EGFiles;
use Controller;
use App\Vtram;
use App\Project;
use App\NextNumber;
use App\Http\Requests\VtramRequest;

class CompanyVtramController extends Controller
{
    protected $identifierPath = 'company.project.vtram';

    public function viewHook()
    {
        $this->actionButtons['methodologies'] = [
            'label' => 'Edit Hazards & Methodologies',
            'path' => '/company/'.$this->args[0].'/project/'.$this->parentId.'/vtram/'.$this->id.'/methodology',
            'icon' => 'receipt',
            'order' => '300',
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

        if (strpos($this->identifierPath, 'previous') === false) {
            if ($this->record->created_from_entity == 'TEMPLATE' && $this->record->created_from_id != null) {
                $this->pillButtons['view_created_from'] = [
                    'label' => 'View Template',
                    'path' => '/template/'.$this->record->created_from_id,
                    'icon' => 'call_missed',
                    'order' => 100,
                    'id' => 'view_created_from',
                ];
            } else if ($this->record->created_from_entity == 'VTRAM' && $this->record->created_from_id != null) {
                $this->pillButtons['view_created_from'] = [
                    'label' => 'View VTRAM Created From',
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
        dd("need to do the submit for approval logic, see teamwork");
    }

    public function viewA3($companyId, $projectId, $vtramId, $otherId = null)
    {
        if ($companyId == null) {
            $companyId = Auth::user()->companyId;
        }
        //$vtram = Vtram::findOrFail($vtramId);
        //return EGFiles::image($vtram->pdf);
        dd("need to get the pdf, do the A3 thing and return as stream, see teamwork (this route handles print and view)");
    }

    public function printA3($companyId, $projectId, $vtramId)
    {
        dd(func_get_args());
    }

    public function store(VtramRequest $request, $companyId, $projectId)
    {
        $request->merge([
            'project_id' => $projectId,
            'company_id' => $companyId,
        ]);
        return parent::_store(func_get_args());
    }

    public function update(VtramRequest $request)
    {
        return parent::_update(func_get_args());
    }

    public function editContent($companyId, $projectId, $vtramId)
    {
        $this->view = 'modules.company.project.vtram.editVtram';
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
}
