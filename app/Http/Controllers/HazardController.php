<?php

namespace App\Http\Controllers;

use Controller;
use Auth;
use App\Hazard;
use App\Http\Requests\HazardRequest;

class HazardController extends Controller
{
    protected $identifierPath = 'hazard';

    public function store(HazardRequest $request, $companyId, $projectId, $vtramId)
    {
        $request->merge([
            'entity' => 'VTRAM',
            'entity_id' => $vtramId,
        ]);

        $response = parent::_store(func_get_args());
        $returnId = explode("/", $response->getTargetUrl());
        return end($returnId);
    }

    public function created($record, $args)
    {
        return $record->id;
    }

    public function update(HazardRequest $request, $companyId, $projectId, $vtramId, $hazard)
    {
        $response = parent::_update(func_get_args());
        $returnId = explode("/", $response->getTargetUrl());
        return end($returnId);
    }

    public function updated($record, $args)
    {
        return $record->id;
    }

    public function delete($id)
    {
        $hazard = Hazard::findOrFail($id);
        if (VTLogic::canUseItem($hazard->entity_id, $hazard->entity)) {
            $hazard->delete();
            return $this->reOrderHazards($hazard); // no need for ->toJson, laravel already does this.
        }
        return "disallow";
    }

    public function reOrderHazards($hazard)
    {
        Hazard::where('entity', $hazard->entity)
            ->where('entity_id', $hazard->entity_id)
            ->where('list_order', '>', $hazard->list_order)
            ->decrement('list_order');
        return Hazard::where('entity', $hazard->entity)
                                 ->where('entity_id', $hazard->entity_id)
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
        $hazard = Hazard::findOrFail($id);
        $existingHazards = Hazard::where('entity', $hazard->entity)
                                 ->where('entity_id', $hazard->entity_id)
                                 ->orderBy('list_order', 'ASC')
                                 ->get();
        if ($direction == 'down') {
            $decrement = $existingHazards->where('list_order', $hazard->list_order + 1)->first();
            $increment = $existingHazards->where('list_order', $hazard->list_order)->first();
        } else {
            $increment = $existingHazards->where('list_order', $hazard->list_order - 1)->first();
            $decrement = $existingHazards->where('list_order', $hazard->list_order)->first();
        }
        if (!is_null($increment) & !is_null($decrement)) {
            $increment->increment('list_order');
            $decrement->decrement('list_order');
        } else {
            toastr()->error('Cannot move this Hazard');
        }
        return $existingHazards->sortBy('list_order')->values();
    }
}
