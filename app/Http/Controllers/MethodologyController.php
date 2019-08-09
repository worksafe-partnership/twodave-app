<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use EGFiles;
use Controller;
use App\Icon;
use App\TableRow;
use App\Methodology;
use App\Instruction;
use Illuminate\Http\Request;
use App\Http\Classes\VTLogic;
use App\Http\Classes\VTConfig;
use App\Http\Requests\MethodologyRequest;
use App\Http\Requests\MethodologyImageRequest;

class MethodologyController extends Controller
{
    protected $identifierPath = 'methodology';

    public function store(MethodologyRequest $request)
    {
        $this->args = func_get_args();
        $vtconfig = new VTConfig((int)end($this->args), $request->entityType);
        $this->user = Auth::user();
        if ($this->user->company_id !== null && $vtconfig->entity !== null && $vtconfig->entity->company_id !== null) {
            if ($this->user->company_id !== $vtconfig->entity->company_id) {
                abort(404);
            }
        }
        $request->merge([
            'entity' => $request->entityType,
            'entity_id' => end($this->args),
        ]);

        $response = parent::_store(func_get_args());
        $returnId = explode("/", $response->getTargetUrl());

        $record = Methodology::find(end($returnId));
        if ($record->category == "TEXT_IMAGE") {
            return ['id' => $record->id, 'image' => $record->image];
        }

        return $record->id;
    }

    public function created($record, $request, $args)
    {
        switch ($record->category) {
            case "SIMPLE_TABLE":
            case "COMPLEX_TABLE":
                $this->sortOutTableRows($record, $request);
                break;
            case "PROCESS":
                $this->sortOutInstructions($record, $request);
                break;
            case "ICON":
                $this->sortOutIcons($record, $request);
                break;
        }
        VTLogic::createPdf($record->entityRecord, null, true);

        return $record->id;
    }

    public function update(MethodologyRequest $request)
    {
        $this->args = func_get_args();
        $methodology = Methodology::findOrFail(end($this->args));
        $vtconfig = new VTConfig($methodology->entity_id, $methodology->entity);
        $this->user = Auth::user();
        if ($this->user->company_id !== null && $vtconfig->entity !== null && $vtconfig->entity->company_id !== null) {
            if ($this->user->company_id !== $vtconfig->entity->company_id) {
                abort(404);
            }
        }
        $response = parent::_update(func_get_args());
        $returnId = explode("/", $response->getTargetUrl());

        $record = Methodology::find(end($returnId));
        if ($record->category == "TEXT_IMAGE") {
            return ['id' => $record->id, 'image' => $record->image];
        }

        return end($returnId);
    }

    public function updated($record, $updated, $request)
    {
        switch ($record->category) {
            case "SIMPLE_TABLE":
                $this->sortOutTableRows($record, $request);
                break;
            case "COMPLEX_TABLE":
                $this->sortOutTableRows($record, $request);
                break;
            case "PROCESS":
                $this->sortOutInstructions($record, $request);
                break;
            case "ICON":
                $this->sortOutIcons($record, $request);
                break;
        }
        VTLogic::createPdf($record->entityRecord, null, true);
        return $record->id;
    }

    public function sortOutTableRows($record, $request)
    {
        TableRow::where('methodology_id', $record->id)->delete();
        // build up your rows
        $rows = [];
        foreach ($request as $key => $value) {
            if (strpos($key, "row_") !== false) {
                $rowCol = explode("__", $key);
                $rows[$rowCol[0]][$rowCol[1]] = $value;
            }
        }

        $order = 1;
        foreach ($rows as $key => $row) {
            $rows[$key]['cols_filled'] = sizeof(array_filter($row));
            $rows[$key]['list_order'] = $order;
            $rows[$key]['methodology_id'] = $record->id;
            $order++;
        }
        TableRow::insert($rows);
    }

    public function sortOutInstructions($record, $request)
    {
        Instruction::where('methodology_id', $record->id)->delete();

        // checkbox translate
        $checks['true'] = 1;
        $checks['false'] = 0;

        // build up rows
        $rows = [];
        foreach ($request as $key => $value) {
            if (strpos($key, "row_") !== false) {
                $rowCol = explode("__", $key);
                if ($rowCol[1] == "heading") { // "heading" checkbox
                    $value = $checks[$value];
                }
                if ($rowCol[1] == "image") {
                    $rows[$rowCol[0]][$rowCol[1]] = (int)$value;
                } else {
                    $rows[$rowCol[0]][$rowCol[1]] = $value;
                }
            }
        }

        // bolt in the rest of the information per row:
        $order = 1;
        foreach ($rows as $key => $row) {
            $rows[$key]['list_order'] = $order;
            $rows[$key]['methodology_id'] = $record->id;
            $order++;
            if (!isset($rows[$key]['image'])) {
                $rows[$key]['image'] = null;
            }
        }
        Instruction::insert($rows);
    }

    public function sortOutIcons($record, $request)
    {
        Icon::where('methodology_id', $record->id)->delete();
        $tables = [];

        $tableTranslate = ['top' => 'MAIN', 'bottom' => 'SUB'];

        // build up your tables
        foreach ($request as $key => $value) {
            if (strpos($key, "icon_list_") !== false) {
                $iconField = explode("_", $key); // icon_list_top_0 = ['icon', 'list', 'top', 0]
                $type = $tableTranslate[$iconField[2]];
                $id = $iconField[3];
                $tables[$type][$id]['image'] = $value;
            } else if (strpos($key, "wording") !== false) {
                $wordingField = explode("_", $key); // wording_top_0 = ['wording', 'top', 0]
                $id = $wordingField[2];
                $type = $tableTranslate[$wordingField[1]];
                $tables[$type][$id]['text'] = $value;
            }
        }

        // bolt in the rest and save
        foreach ($tables as $type => $table) {
            foreach ($table as $listOrder => $row) {
                $table[$listOrder]['list_order'] = $listOrder;
                $table[$listOrder]['type'] = $type;
                $table[$listOrder]['methodology_id'] = $record->id;
            }
            Icon::insert($table);
        }
    }

    public function delete($id)
    {
        $methodology = Methodology::findOrFail($id);
        $vtconfig = new VTConfig($methodology->entity_id, $methodology->entity);
        $this->user = Auth::user();
        if ($this->user->company_id !== null && $vtconfig->entity !== null && $vtconfig->entity->company_id !== null) {
            if ($this->user->company_id !== $vtconfig->entity->company_id) {
                abort(404);
            }
        }
        if (VTLogic::canUseItem($methodology->entity_id, $methodology->entity)) {
            $methodology->delete();
            $this->reOrderMethodologies($methodology);
            DB::table('hazards_methodologies')->where('methodology_id', $id)->delete();
            return 'allow';
        }
        return "disallow";
    }

    public function reOrderMethodologies($methodology)
    {
        Methodology::where('entity', $methodology->entity)
            ->where('entity_id', $methodology->entity_id)
            ->where('list_order', '>', $methodology->list_order)
            ->decrement('list_order');
        return Methodology::where('entity', $methodology->entity)
                                 ->where('entity_id', $methodology->entity_id)
                                 ->orderBy('list_order', 'ASC')
                                 ->get();
    }

    public function moveUp($id)
    {
        return $this->move($id, "up");
    }

    public function moveDown($id)
    {
        return $this->move($id, "down");
    }

    public function move($id, $direction)
    {
        $methodology = Methodology::findOrFail($id);
        $vtconfig = new VTConfig($methodology->entity_id, $methodology->entity);
        $this->user = Auth::user();
        if ($this->user->company_id !== null && $vtconfig->entity !== null && $vtconfig->entity->company_id !== null) {
            if ($this->user->company_id !== $vtconfig->entity->company_id) {
                abort(404);
            }
        }
        $existingMethodologies = Methodology::where('entity', $methodology->entity)
                                 ->where('entity_id', $methodology->entity_id)
                                 ->orderBy('list_order', 'ASC')
                                 ->get();
        if ($direction == 'down') {
            $decrement = $existingMethodologies->where('list_order', $methodology->list_order + 1)->first();
            $increment = $existingMethodologies->where('list_order', $methodology->list_order)->first();
        } else {
            $increment = $existingMethodologies->where('list_order', $methodology->list_order - 1)->first();
            $decrement = $existingMethodologies->where('list_order', $methodology->list_order)->first();
        }
        if (!is_null($increment) & !is_null($decrement)) {
            $increment->increment('list_order');
            $decrement->decrement('list_order');
            return 'allow';
        } else {
            toast()->error('Cannot move this Methodology');
            return 'disallow';
        }
    }

    public function addImage(MethodologyImageRequest $request)
    {
        $data = EGFiles::store($request, ['image' => 'image']);
        return $data['image'];
    }

    public function editImage(MethodologyImageRequest $request)
    {
        $data = EGFiles::store($request, ['image' => 'image']);
        return $data['image'];
    }

    public function deleteImage(Request $request)
    {
        $file = EGFiles::find($request['file']);
        if ($file) {
            $file->delete();
        }
        return "deleted";
    }
}
