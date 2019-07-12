<?php

namespace App\Http\Classes;

use App\Template;
use App\Vtram;

class VTConfig
{
    public $entity = null;
    public $entityId = null;
    protected $entityType = null;

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
            case is_int($e) && $type != null && in_array($type, ['VTRAM', 'TEMPLATE']):
                if ($type == 'VTRAM') {
                    $this->entity = Vtram::findOrFail($e);
                } else {
                    $this->entity = Template::findOrFail($e);
                }
                $this->entityType = 'TEMPLATE';
                $this->entityId = $e;
                break;
            default:
                throw new \Exception("Error Instantiating VtConfig");
                break;
        }
    }
}
