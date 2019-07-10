<?php

namespace App\Http\Classes;

use App\Template;
use App\Vtram;

class VtConfig
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

class VTLogic
{
    protected $config = null;
    public function __construct($entityId, $entityType = null)
    {
        $this->config = new VTConfig($entityId, $entityType);
    }

    // Static functions
    public static function createA3Pdf($entityId, $entityType = null)
    {
        $config = new VTConfig($entityId, $entityType);
        dd('Now do PDF with $config->entity and return as stream, see teamwork (this route handles print and view)');
    }

    public static function submitForApproval($entityId, $entityType = null)
    {
        $config = new VTConfig($entityId, $entityType);
        if (in_array($config->entity->status, ['NEW', 'REJECTED'])) {
            $newStatus = 'PENDING';
        } else if (in_array($config->entity->status, ['EXTERNAL_REJECT','AWAITING_EXTERNAL']) && ($this->config->entityType == 'VTRAM' && $this->config->entity->project->principle_contractor)) {
            $newStatus = 'AWAITING_EXTERNAL';
            dd("SEND PRINCIPLE CONTRACTOR EMAIL HERE");
        }
        if (isset($newStatus)) {
            $config->entity->update([
                'status' => $newStatus,
            ]);
        }
    }
}
