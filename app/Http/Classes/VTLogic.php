<?php

namespace App\Http\Classes;

use DB;
use Mail;
use Auth;
use Bhash;
use EGFiles;
use App\Icon;
use App\Vtram;
use App\Hazard;
use App\Approval;
use App\TableRow;
use App\Template;
use App\UniqueLink;
use app\Instruction;
use App\Methodology;
use App\NextNumber;
use iio\libmergepdf\Merger;
use iio\libmergepdf\Pages;
use App\Http\Classes\VTFiles;
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

        $file = VTFiles::findOrFail($config->entity->pdf);
        $path = storage_path('app/'.$file->location);
        $merger = new Merger;
        $merger->addFile($path, new Pages(4));
        $merger->addFile($path, new Pages(1));
        $merger->addFile($path, new Pages(2));
        $merger->addFile($path, new Pages(3));

        $response = \Response::make($merger->merge(), 200);
        $response->header('Content-Type', 'application/pdf');
        return $response;
    }

    public static function createPdf($entityId, $entityType = null, $force = false)
    {
        $config = new VTConfig($entityId, $entityType);
        if (false) {
        //if ($config->entity->status == 'PREVIOUS' || ($config->entity->pdf != null && !$force)) {
            return EGFiles::image($config->entity->pdf);            
        }
        $logo = null;
        if ($config->entity->logo !== null) {
            $logo = $config->entity->logo;
        } else if ($config->entity->company != null && $config->entity->company->logo != null) {
            $logo = $config->entity->company->logo;
        }
        if ($logo != null) {
            $file = EGFiles::download($logo)->getFile()->getPathName() ?? null;
        } else {
            $file = null;
        }

        $company = $config->entity->company;
        if ($company != null) {
            $riskList = [
                0 => $company->no_risk_character,
                1 => $company->low_risk_character,
                2 => $company->med_risk_character,
                3 => $company->high_risk_character,
            ];
        } else {
            $riskList = [
                0 => '#',
                1 => 'L',
                2 => 'M',
                3 => 'H',
            ];
        }
        $data = [
            'entity' => $config->entity,
            'type' => $config->entityType,
            'logo' => $file,
            'riskList' => $riskList,
            'whoIsRisk' => config('egc.hazard_who_risk')
        ];
        $pdf = \PDF::loadView('pdf.main_report', $data)
            ->setOption('margin-top', 10)
            ->setOption('margin-left', 5)
            ->setOption('margin-right', 5)
            ->setOption('margin-bottom', 5);
//        return view('pdf.main_report', $data);
        $returnStream = $pdf->stream();
        $instances = null;
        preg_match_all('/Count [0-9]+/', $pdf->download()->getContent(), $instances);
        $count = 0;
        foreach ($instances[0] as $match) {
            $m = (int)str_replace('Count ', '', $match);
            if ($m > $count) {
                $count = $m;
            }   
        }
        $file = VTFiles::saveOrUpdate($pdf->download()->getContent(), $config->entity, $config->entityType);
        if ($file == null) {
            toast()->error("Failed to save PDF");
        } else {
            $res = $config->entity->update([
                'pdf' => $file->id,
                'pages_in_pdf' => $count,
            ]);
        }
        return $returnStream;
    }

    public static function submitForApproval($entityId, $entityType = null)
    {
        $config = new VTConfig($entityId, $entityType);
        self::createPdf($config->entity, null, true);
        if (in_array($config->entity->status, ['NEW', 'REJECTED'])) {
            $newStatus = 'PENDING';
        } else if (in_array($config->entity->status, ['EXTERNAL_REJECT']) && ($config->entityType == 'VTRAM' && $config->entity->project->principle_contractor)) {
            $newStatus = 'AWAITING_EXTERNAL';
            self::sendPCApprovalEmail($config);
        }
        if (isset($newStatus)) {
            $config->entity->update([
                'status' => $newStatus,
                'submitted_by' => Auth::id(),
                'submitted_date' => date('Y-m-d')
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
                'title' => 'Task Description',
            ],
            [
                'type' => 'SIMPLE_TABLE',
                'name' => 'plant_and_equipment',
                'title' => 'Plant and Equipment',
            ],
            [
                'type' => 'TEXT',
                'name' => 'disposing_of_waste',
                'title' => 'Disposing of Waste',
            ],
            [
                'type' => 'TEXT',
                'name' => 'accident_reporting',
                'title' => 'Accident Reporting',
            ],
            [
                'type' => 'TEXT_IMAGE',
                'name' => 'first_aid',
                'title' => 'First Aid',
            ],
            [
                'type' => 'TEXT',
                'name' => 'noise',
                'title' => 'Noise',
            ],
            [
                'type' => 'TEXT',
                'name' => 'working_at_height',
                'title' => 'Working at Height',
            ],
            [
                'type' => 'TEXT',
                'name' => 'manual_handling',
                'title' => 'Manual Handling',
            ],
        ];
        $order = 1;
        foreach ($list as $item) {
            if (strlen($config->entity->{$item['name']}) > 0) {
                Methodology::create([
                    'title' => $item['title'],
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

    public static function copyEntity($original, $cloned = null)
    {
        return DB::transaction(function () use ($original, $cloned) {
            if ($cloned == null) {
                $cloned = $original->replicate();
                if ($original->original_id == null) {
                    $cloned->original_id = $original->id;
                }
                if ($cloned instanceof Vtram) {
                    $cloned->created_from_entity = $original instanceof Vtram ? 'VTRAM' : 'TEMPLATE';
                    $cloned->created_from_id = $original->id;
                    $nextNumber = NextNumber::where('company_id', '=', $cloned->company_id)
                        ->first();
                    if (is_null($nextNumber)) {
                        $nextNumber = NextNumber::create([
                            'company_id' => $cloned->company_id,
                            'number' => 1,
                        ]);
                    }
                    $cloned->number = $nextNumber->number;
                    $nextNumber->increment('number');
                } else {
                    $cloned->created_from = $original->id;
                }
                $cloned->status = 'NEW';
                $cloned->revision_number = null;
                $cloned->review_due = null;
                $cloned->approved_date = null;
                $cloned->updated_by = null;
                $cloned->created_by = Auth::id();
                $cloned->submitted_by = null;
                $cloned->submitted_date = null;
                $cloned->date_replaced = null;
                $cloned->resubmit_by = null;
                $cloned->save();
            }
            $insertHazards = [];
            foreach ($original->hazards as $hazard) {
                $newHazard = $hazard->toArray();
                unset($newHazard['id']);
                $newHazard['entity'] = $cloned instanceof Vtram ? 'VTRAM' : 'TEMPLATE';
                $newHazard['entity_id'] = $cloned->id;
                $insertHazards[] = $newHazard;
            }
            Hazard::insert($insertHazards);

            $insertIcons = [];
            $insertTableRows = [];
            $insertInstructions = [];
            foreach ($original->methodologies as $methodology) {
                $newMeth = $methodology->toArray();
                unset($newMeth['id']);
                $newMeth['entity'] = $cloned instanceof Vtram ? 'VTRAM' : 'TEMPLATE';
                $newMeth['entity_id'] = $cloned->id;
                $meth = Methodology::create($newMeth);
                foreach ($methodology->icons as $icon) {
                    $newIcon = $icon->toArray();
                    unset($newIcon['id']);
                    $newIcon['methodology_id'] = $meth->id;
                    $insertIcons[] = $newIcon;
                }
                foreach ($methodology->instructions as $instruction) {
                    $newInstruction = $instruction->toArray();
                    unset($newInstruction['id']);
                    $newInstruction['methodology_id'] = $meth->id;
                    $insertInstructions[] = $newInstruction;
                }
                foreach ($methodology->tableRows as $row) {
                    $newRow = $row->toArray();
                    unset($newRow['id']);
                    $newRow['methodology_id'] = $meth->id;
                    $insertTableRows[] = $newRow;
                }
            }
            Icon::insert($insertIcons);
            TableRow::insert($insertTableRows);
            Instruction::insert($insertInstructions);
            return $cloned;
        });
    }
}
