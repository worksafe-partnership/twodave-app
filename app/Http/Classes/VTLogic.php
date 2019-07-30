<?php

namespace App\Http\Classes;

use Mail;
use Auth;
use Bhash;
use App\Approval;
use App\UniqueLink;
use App\Methodology;
use App\Mail\PrincipleContractorEmail;

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
        dd('Now do PDF with $config->entity and return as stream, see teamwork (this route handles print and view) make sure it is a3');
    }

    public static function createPdf($entityId, $entityType = null)
    {
        $config = new VTConfig($entityId, $entityType);
        dd('Now do PDF with $config->entity and return as stream, see teamwork (this route handles print and view)');
    }

    public static function submitForApproval($entityId, $entityType = null)
    {
        $config = new VTConfig($entityId, $entityType);
        if (in_array($config->entity->status, ['NEW', 'REJECTED'])) {
            $newStatus = 'PENDING';
        } else if (in_array($config->entity->status, ['EXTERNAL_REJECT']) && ($config->entityType == 'VTRAM' && $config->entity->project->principle_contractor)) {
            $newStatus = 'AWAITING_EXTERNAL';
            self::sendPCApprovalEmail($config);
        }
        if (isset($newStatus)) {
            $config->entity->update([
                'status' => $newStatus,
            ]);
        }
    }

    public static function sendPcApprovalEmail($config)
    {
        $links = UniqueLink::where('email', '=', $config->entity->project->principle_contractor_email)
            ->first();
        if ($links == null) {
            // CREATE NEW ONE
            $hash = new Bhash();
            $key = str_replace(['.', '/'], ['$DOT$', '$FS$'], $hash->make($config->entity->name.$config->entity->reference.$config->entity->status.rand(0, 100000)));
            $links = UniqueLink::create([
                'email' => $config->entity->project->principle_contractor_email,
                'unique_link' => $key,
            ]);
        }
        Mail::to(env('MAIL_LIVE') ? $config->entity->project->principle_contractor_email : env('DEV_EMAIL'))
            ->send(new PrincipleContractorEmail($links->unique_link, $config->entity->project));
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

    public static function createDefaultMethodologies($entityId, $entityType)
    {
        $config = new VTConfig($entityId, $entityType);

        $list = [
            [
                'type' => 'TEXT',
                'name' => 'task_description',
            ],
            [
                'type' => 'SIMPLE_TABLE',
                'name' => 'plant_and_equipment',
            ],
            [
                'type' => 'TEXT',
                'name' => 'disposing_of_waste',
            ],
            [
                'type' => 'TEXT',
                'name' => 'accident_reporting',
            ],
            [
                'type' => 'TEXT_IMAGE',
                'name' => 'first_aid',
            ],
            [
                'type' => 'TEXT',
                'name' => 'noise',
            ],
            [
                'type' => 'TEXT',
                'name' => 'working_at_height',
            ],
            [
                'type' => 'TEXT',
                'name' => 'manual_handling',
            ],
        ];
        $order = 1;
        foreach ($list as $item) {
            if (strlen($config->entity->{$item['name']}) > 0) {
                Methodology::create([
                    'category' => $item['type'],
                    'entity' => $config->entityType,
                    'entity_id' => $config->entityId,
                    'text_before' => $config->entity->{$item['name']},
                    'list_order' => $order,
                ]);
                $order++;
            }
        }
    }

    public static function canReview($entityId, $entityType = null, $status = ['PENDING'])
    {
        $user = Auth::user();
        $config = new VTConfig($entityId, $entityType);
        if (!in_array($config->entity->status, $status)) {
            return false;
        }
        if ($user->id == $config->entity->submitted_by) {
            return false;
        }
        //user roles based
        $subRole = $config->entity->submitted->roles->first();
        $userRole = $user->roles->first();
        switch ($userRole->slug) {
            case 'supervisor':
                return false;
                break;
            case 'project_admin':
                if (in_array($subRole->slug, ['supervisor'])) {
                    return true;
                }
                break;
            case 'contract_manager':
                if (in_array($subRole->slug, ['supervisor','project_admin'])) {
                    return true;
                }
                break;
            case 'company_admin':
                if (in_array($subRole->slug, ['supervisor','project_admin','contract_manager','company_admin'])) {
                    return true;
                }
                break;
            case 'admin':
                return true;
                break;
            case 'evergreen':
                return true;
                break;
        }
        
        return false;
    }
}
