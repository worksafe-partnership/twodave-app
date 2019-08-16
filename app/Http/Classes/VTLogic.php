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
use App\Instruction;
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
    public static function createA3Pdf($entityId, $entityType = null, $force = false)
    {
        $config = new VTConfig($entityId, $entityType);

        if ($force) {
            self::createPdf($config->entity, null, true);
        }
        $file = VTFiles::findOrFail($config->entity->pdf);
        $path = storage_path('app/'.$file->location);
        $merger = new Merger;
        $merger->addFile($path, new Pages(4));
        $merger->addFile($path, new Pages(1));
        $merger->addFile($path, new Pages(2));
        $merger->addFile($path, new Pages(3));

        $response = \Response::make($merger->merge(), 200);
        $response->header('Content-Type', 'application/pdf');
        $response->header('Content-Disposition', 'filename="'.$config->entity->name.'.pdf"');
        return $response;
    }

    public static function createPdf($entityId, $entityType = null, $force = false)
    {
        $config = new VTConfig($entityId, $entityType);
        if ($config->entity->status == 'PREVIOUS' || ($config->entity->pdf != null && !$force)) {
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
        if ($config->entity->approved != null && $config->entity->approved->signature != null) {
            $approvedSig = EGFiles::download($config->entity->approved->signature)->getFile()->getPathName() ?? null;
        } else {
            $approvedSig = null;
        }
        if ($config->entity->submitted != null && $config->entity->submitted->signature != null) {
            $submittedSig = EGFiles::download($config->entity->submitted->signature)->getFile()->getPathName() ?? null;
        } else {
            $submittedSig = null;
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
        $titleBlockText = self::replaceContent($config->entity, 'main_description');
        $postRiskText = self::replaceContent($config->entity, 'post_risk_assessment_text');
        foreach ($config->entity->methodologies as &$meth) {
            $meth = self::replaceContent($meth, [
                'text_before',
                'text_after',
            ]);
        }
        // First page height calculation
        $height = 120;
        if ($config->entityType == 'VTRAM' && $config->entity->project->show_contact) {
            $height += 90;
        }
        if ($config->entity->show_responsible_person) {
            $height += 30;
        }
        if (($config->entity->submitted != null && strlen($config->entity->submitted->name) > 26) || ($config->entity->approved != null && strlen($config->entity->approved->name) > 26)) {
            $tableHeight = 100;
        }
        $mainHeight = $height;
        if (isset($tableHeight)) {
            $mainHeight += $tableHeight;
        } else {
            $mainHeight += 50;
        }
        if ($config->entity->show_responsible_person) {
            $mainHeight += 25;
        }
        if ($config->entity->show_area) {
            $mainHeight += 25;
        }
        $mainHeight += 30; // method statements
        $mainHeight += 60; // task name
        // Can we improve this to get the html height of the ckeditor field?
        $titleBlockTextLength = strlen($config->entity->main_description) / 134;
        $mainHeight += $titleBlockTextLength * 25;
        $brs = [];
        preg_match_all("/&nbsp;/", $config->entity->main_description, $brs);
        $mainHeight += count($brs) * 20;
        $mainHeight += 250;
        // End first page height calculation

        $data = [
            'entity' => $config->entity,
            'titleBlockText' => $titleBlockText,
            'postRiskText' => $postRiskText,
            'type' => $config->entityType,
            'logo' => $file,
            'approvedSig' => $approvedSig,
            'submittedSig' => $submittedSig,
            'tableHeight' => $tableHeight ?? null,
            'riskList' => $riskList,
            'whoIsRisk' => config('egc.hazard_who_risk'),
            'height' => $height,
            'startingHeight' => $mainHeight,//self::calculateStartingHeight($config),
        ];

        $pdf = \PDF::loadView('pdf.main_report', $data)
            ->setOption('margin-top', 10)
            ->setOption('margin-left', 5)
            ->setOption('margin-right', 5)
            ->setOption('margin-bottom', 5);
#        return view('pdf.main_report', $data);
        $returnStream = $pdf->stream($config->entity->name.'.pdf');
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

    public static function calculateStartingHeight($config)
    {
        $height = 0;

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
            return Approval::withTrashed()->where('entity_id', $config->entityId)
                                ->where('approvals.entity', $config->entityType)
                                ->get();
        }

        if (!is_null($entityType) && in_array($status, ['REJECTED', 'EXTERNAL_REJECT', 'CURRENT', 'PREVIOUS'])) {
            return Approval::withTrashed()->where('entity_id', $config->entityId)
                                ->where('approvals.entity', $config->entityType)
                                ->when(in_array($status, ['CURRENT', 'PREVIOUS']), function ($atTime) {
                                    $atTime->where('status_at_time', 'ACCEPT');
                                })
                                ->get();
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
                $methodology = [
                    'title' => $item['title'],
                    'category' => $item['type'],
                    'entity' => $config->entityType,
                    'entity_id' => $config->entityId,
                    'text_before' => $config->entity->{$item['name']},
                    'list_order' => $order,
                ];

                if ($item['name'] == 'first_aid') {
                    $file = VTFiles::saveNew(file_get_contents(public_path('first_aid_logo.png')), $config->entity, $config->entityType, time().'first_aid.png');
                    if ($file != false) {
                        $methodology['image'] = $file->id;
                    }
                }

                Methodology::create($methodology);
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
                $cloned->approved_by = null;
                $cloned->approved_date = null;
                $cloned->updated_by = null;
                $cloned->created_by = Auth::id();
                $cloned->submitted_by = null;
                $cloned->submitted_date = null;
                $cloned->date_replaced = null;
                $cloned->resubmit_by = null;
                $cloned->save();
                if ($original instanceof Vtram) {
                    Vtram::where('current_id', '=', $original->id)
                        ->update([
                            'current_id' => $cloned->id,
                        ]);
                } else {
                    Template::where('current_id', '=', $original->id)
                        ->update([
                            'current_id' => $cloned->id,
                        ]);
                }

                $original->current_id = $cloned->id;
                $original->status = 'PREVIOUS';
                $original->save();
            }
            $hazardOld = [];
            foreach ($original->hazards as $hazard) {
                $newHazard = $hazard->toArray();
                unset($newHazard['id']);
                $newHazard['entity'] = $cloned instanceof Vtram ? 'VTRAM' : 'TEMPLATE';
                $newHazard['entity_id'] = $cloned->id;
                $copy = Hazard::create($newHazard);
                $hazardOld[$hazard->id] = $copy->id;
            }

            $insertIcons = [];
            $insertTableRows = [];
            $insertInstructions = [];
            $insertHazMethLink = [];
            foreach ($original->methodologies as $methodology) {
                $newMeth = $methodology->toArray();
                unset($newMeth['id']);
                $newMeth['entity'] = $cloned instanceof Vtram ? 'VTRAM' : 'TEMPLATE';
                $newMeth['entity_id'] = $cloned->id;
                $meth = Methodology::create($newMeth);
                $hms = DB::table('hazards_methodologies')
                    ->where('methodology_id', '=', $methodology->id)
                    ->get();
                foreach ($hms as $hm) {
                    $insertHazMethLink[] = [
                        'hazard_id' => $hazardOld[$hm->hazard_id],
                        'methodology_id' => $meth->id,
                    ];
                }
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
            DB::table('hazards_methodologies')
                ->insert($insertHazMethLink);
            return $cloned;
        });
    }

    public static function replaceContent($entity, $fields)
    {
        $replacements = [];
        if ($entity instanceof Template || $entity instanceof Vtram) {
            $replacements['{{title}}'] = $entity->name;
            $replacements['{{company_name}}'] = $entity->company->name;
            $replacements['{{company_short_name}}'] = $entity->company->short_name;
        } else {
            $replacements['{{title}}'] = $entity->entityRecord->name;
            $replacements['{{company_name}}'] = $entity->entityRecord->company->name;
            $replacements['{{company_short_name}}'] = $entity->entityRecord->company->short_name;
        }
        if (is_array($fields)) {
            foreach ($fields as $field) {
                $entity->{$field} = str_replace(array_keys($replacements), array_values($replacements), $entity->{$field});
            }
            return $entity;
        }
        return str_replace(array_keys($replacements), array_values($replacements), $entity->{$fields});
    }
}
