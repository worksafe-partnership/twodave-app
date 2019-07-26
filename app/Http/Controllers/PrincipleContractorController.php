<?php

namespace App\Http\Controllers;

use Controller;
use App\Vtram;
use App\UniqueLink;
use Illuminate\Http\Request;

class PrincipleContractorController extends Controller
{
    public function vtramsList()
    {

    }

    public function viewVtrams($uniqueLink, $id)
    {
        $link = UniqueLink::where('unique_link', '=', $uniqueLink)
            ->firstOrFail();
        $vtrams = Vtram::where('id', '=', $id)
            ->whereHas('project', function ($q) use ($link) {
                return $q->where('principle_contractor_email', '=', $link->email);
            })
            ->where('status', '=', 'AWAITING_EXTERNAL')
            ->firstOrFail();

        $this->view = 'modules.company.project.vtram.display';
        $this->pageType = 'view';
        $this->record = $vtrams;
        if ($this->record->pages_in_pdf == 4) {
            $path = 'javascript: window.open("'.$this->record->id.'/view_a3", "_blank");window.open("'.$this->record->id.'/approve", "_self");window.focus();';
        } else {
            $path = 'javascript: window.open("/image/'.$this->record->pdf.'", "_blank");window.open("'.$this->record->id.'/approve", "_self");window.focus();';
        }
        $this->actionButtons['approve_vtrams'] = [
            'label' => 'Approve VTRAMS',
            'path' => $path,
            'icon' => 'playlist_add_check',
            'id' => 'approve_vtrams',
            'order'=> 100,
        ];
        $this->disableCreate = true;
        $this->disableEdit = true;
        $this->disableDelete = true;

        parent::setup();
        parent::_buildProperties();
        return parent::_renderView("layouts.custom");
    }

    public function viewApproval()
    {

    }

    public function store()
    {

    }

    public function viewA3()
    {

    }
}
