<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Hazard;
use Controller;
use App\Http\Classes\VTLogic;
use App\Http\Classes\VTConfig;
use App\Http\Requests\HazardRequest;

class HazardController extends Controller
{
    protected $identifierPath = 'hazard';

    public function store(HazardRequest $request)
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
        DB::table('hazards_methodologies')->where('hazard_id', $record->id)->delete();
        if (isset($request['selectedMethodologies'])) {
            $methodologies = [];
            foreach ($request['selectedMethodologies'] as $link) {
                $methodologies[] = ['hazard_id' => $record->id, 'methodology_id' => $link];
            }
            DB::table('hazards_methodologies')->insert($methodologies);
        }

        return $record->id;
    }

    public function update(HazardRequest $request)
    {
        $this->args = func_get_args();
        $hazard = Hazard::findOrFail(end($this->args));
        $vtconfig = new VTConfig($hazard->entity_id, $hazard->entity);
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

    public function updated($updated, $original, $request)
    {
        DB::table('hazards_methodologies')->where('hazard_id', $updated->id)->delete();
        if (isset($request['selectedMethodologies'])) {
            $methodologies = [];
            foreach ($request['selectedMethodologies'] as $link) {
                $methodologies[] = ['hazard_id' => $updated->id, 'methodology_id' => $link];
            }
            DB::table('hazards_methodologies')->insert($methodologies);
        }
        return $updated->id;
    }

    public function delete($id)
    {
        $hazard = Hazard::findOrFail($id);
        $vtconfig = new VTConfig($hazard->entity_id, $hazard->entity);
        $this->user = Auth::user();
        if ($this->user->company_id !== null && $vtconfig->entity !== null && $vtconfig->entity->company_id !== null) {
            if ($this->user->company_id !== $vtconfig->entity->company_id) {
                abort(404);
            }
        }
        if (VTLogic::canUseItem($hazard->entity_id, $hazard->entity)) {
            $hazard->delete();
            DB::table('hazards_methodologies')->where('hazard_id', $id)->delete();
            $this->reOrderHazards($hazard);
            return 'allow';
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
        $vtconfig = new VTConfig($hazard->entity_id, $hazard->entity);
        $this->user = Auth::user();
        if ($this->user->company_id !== null && $vtconfig->entity !== null && $vtconfig->entity->company_id !== null) {
            if ($this->user->company_id !== $vtconfig->entity->company_id) {
                abort(404);
            }
        }
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
            return 'allow';
        } else {
            toast()->error('Cannot move this Hazard');
            return 'disallow';
        }
    }
}
