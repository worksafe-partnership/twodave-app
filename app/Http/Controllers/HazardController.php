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

    public function update(HazardRequest $request)
    {
        return parent::_update(func_get_args());
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
}
