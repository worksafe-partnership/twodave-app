<?php

namespace App\Http\Classes;

use App\Approval;

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
        } else if (in_array($config->entity->status, ['EXTERNAL_REJECT','AWAITING_EXTERNAL']) && ($config->entityType == 'VTRAM' && $config->entity->project->principle_contractor)) {
            $newStatus = 'AWAITING_EXTERNAL';
            dd("SEND PRINCIPLE CONTRACTOR EMAIL HERE");
        }
        if (isset($newStatus)) {
            $config->entity->update([
                'status' => $newStatus,
            ]);
        }
    }

    public static function getComments($entityId, $status, $entityType = null)
    {
        $config = new VTConfig($entityId, $entityType);
        //if status is null, show all
        if (!is_null($entityType) && !$status) {
            return Approval::where('entity_id', $config->entityId)
                                ->where('approvals.entity', $config->entityType)
                                ->get(); // returning everything for now, can lock down when I know what to display
        }

        if (!is_null($entityType) && in_array($status, ['REJECTED', 'EXTERNAL_REJECT', 'CURRENT', 'PREVIOUS'])) {
            return Approval::where('entity_id', $config->entityId)
                                ->where('approvals.entity', $config->entityType)
                                ->when(in_array($status, ['CURRENT', 'PREVIOUS']), function ($atTime) {
                                    $atTime->where('status_at_time', 'ACCEPT');
                                })
                                ->get(); // returning everything for now, can lock down when I know what to display
        }
        return collect([]);
    }

    public static function canUseItem($entityId, $entityType)
    {
        $config = new VTConfig($entityId, $entityType);
        $user = Auth::user();
        if (is_null($user->company_id)) {
            return true;
        }
        if ($config->entity->company_id == $user->company_id) {
            return true;
        }

        return false;
    }
}
