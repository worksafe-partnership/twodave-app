<?php

namespace App\Http\Classes;

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
