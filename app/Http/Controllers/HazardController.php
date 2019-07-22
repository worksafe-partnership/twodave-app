<?php

namespace App\Http\Controllers;

use Controller;
use Auth;
use App\Hazard;
use App\Http\Requests\HazardRequest;

class HazardController extends Controller
{
    protected $identifierPath = 'company.project.vtram.hazard';

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

    // MB: Andy said to put it in this way - Instead of returning the full url it's only returning <APP_URL>/hazard_id
    // which is apparently "less of a hack" - Andy's words, not mine.
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

    // MB: Andy said to put it in this way - Instead of returning the full url it's only returning <APP_URL>/hazard_id
    // which is apparently "less of a hack" - Andy's words, not mine.
    public function updated($record, $args)
    {
        return $record->id;
    }

    public function delete($id)
    {
        $user = Auth::User();
        $hazard = Hazard::findOrFail($id);
        if (is_null($user->company_id)) {
            $allow = true;
        } else {
            $type = strtolower($hazard->entity);
            $type = ucfirst($type);
            $class = "\App\\".$type;
            $parent = $class::findOrFail($hazard->entity_id);
            if ($parent->company_id == $user->company_id) {
                $allow = true;
            }
        }

        if (isset($allow)) {
            $hazard->delete();
            return $this->reOrderHazards($hazard); // no need for ->toJson, laravel already does this.
        }
        return "disallow";
    }

    public function reOrderHazards($hazard)
    {
        $existingHazards = Hazard::where('entity', $hazard->entity)
                                 ->where('entity_id', $hazard->entity_id)
                                 ->orderBy('list_order', 'ASC')
                                 ->get();

        $newOrder = 1;
        foreach ($existingHazards as $record) {
            $record['list_order'] = $newOrder++;
            $record->save();
        }
        return $existingHazards;
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

        $count = $existingHazards->count();

        if ($count > 1) {
            foreach ($existingHazards as $key => $item) {
                if ($item->id == $id) {
                    $tempItem = $item->replicate();
                    if ($direction == "up" && $item->list_order != 1) { // only allow if it's not already top.
                        $item->update(['list_order' => $existingHazards[$key-1]->list_order]);
                        $existingHazards[$key-1]->update(['list_order' => $tempItem->list_order]);
                    } else if ($direction == "down" && $item->list_order != $count) { // only allow if it's not already bottom.
                        $item->update(['list_order' => $existingHazards[$key+1]->list_order]);
                        $existingHazards[$key+1]->update(['list_order' => $tempItem->list_order]);
                    }
                    break;
                }
            }           
        }
        // reorder based on list order then reset the collection's keys, otherwise it falls over.
        return $existingHazards->sortBy('list_order')->values();
    }

}
