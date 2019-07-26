<?php

namespace App\Http\Controllers;

use Auth;
use Controller;
use App\Methodology;
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

    public function updated($record, $request, $args)
    {
        return $record->id;
    }
}
