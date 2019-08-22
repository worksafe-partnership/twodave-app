<?php

namespace App\Http\Classes;

use App\Template;
use App\Vtram;

class VTConfig
{
    public $entity = null;
    public $entityId = null;
    public $entityType = null; // we use this in VTLogic so if it needs to be protected, make a getter function.

    public function __construct($e, $type = null)
    {
        switch (true) {
            case $e instanceof Vtram:
                $this->entity = $e;
                $this->entityId = $e->id;
                $this->entityType = 'VTRAM';
                break;
            case $e instanceof Template:
                $this->entity = $e;
                $this->entityId = $e->id;
                $this->entityType = 'TEMPLATE';
                break;
            case is_int((int)$e) && $type != null && in_array($type, ['VTRAM', 'TEMPLATE']):
                if ($type == 'VTRAM') {
                    $this->entity = Vtram::withTrashed()->findOrFail($e);
                    $this->entityType = 'VTRAM';
                } else {
                    $this->entity = Template::withTrashed()->findOrFail($e);
                    $this->entityType = 'TEMPLATE';
                }

                $this->entityId = $e;
                break;
            default:
                throw new \Exception("Error Instantiating VtConfig");
                break;
        }
    }
}
