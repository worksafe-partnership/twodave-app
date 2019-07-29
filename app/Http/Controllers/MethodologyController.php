<?php

namespace App\Http\Controllers;

use Auth;
use Controller;
use App\Methodology;
use App\TableRow;
use App\Http\Classes\VTLogic;
use App\Http\Classes\VTConfig;
use App\Http\Requests\MethodologyRequest;

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
        return end($returnId);
    }

    public function created($record, $request, $args)
    {
        if ($record->category == "SIMPLE_TABLE") {
            $this->sortOutSimpleTable($record, $request);
        }
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
        return end($returnId);
    }

    public function updated($record, $updated, $request)
    {
        if ($record->category == "SIMPLE_TABLE") {
            $this->sortOutSimpleTable($record, $request);
        }
        return $record->id;
    }

    public function sortOutSimpleTable($record, $request)
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
}
