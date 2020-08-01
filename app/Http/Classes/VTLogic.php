<?php

namespace App\Http\Classes;

use DB;
use Mail;
use Auth;
use Bhash;
use Carbon;
use EGFiles;
use Storage;
use App\Icon;
use App\Vtram;
use App\Hazard;
use App\Company;
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

        // if it's a PDF, just return it straight away.
        if ($config->entityType == "VTRAM" && $config->entity->vtram_is_file) {
            $file = EGFiles::findOrFail($config->entity->vtram_file);
            $response = \Response::make(file_get_contents(storage_path()."/app/".$file->location), 200);
            $response->header('Content-Type', 'application/pdf');
            $response->header('Content-Disposition', 'filename="'.$file->filename.'.pdf"');
            return $response;
        }

        if ($force) {
            self::createPdf($config->entity, null, true, true);
        }
        $file = VTFiles::findOrFail($config->entity->pdf);
        $path = storage_path('app/'.$file->location);
        $merger = new Merger;
        // NEED TO HAVE LAST PAGE FIRST IN 4 OR 2 - if so uncomment below
        if ($config->entity->pages_in_pdf == 4) {
            $merger->addFile($path, new Pages(4));
            $merger->addFile($path, new Pages(1));
            $merger->addFile($path, new Pages(2));
            $merger->addFile($path, new Pages(3));
        } else if ($config->entity->pages_in_pdf == 3) {
            $merger->addFile($path, new Pages(3));
            $merger->addFile($path, new Pages(1));
            $merger->addFile($path, new Pages(2));
            $merger->addFile(public_path().'/blank_page.pdf', new Pages(1));
        } else {
            $merger->addFile($path, new Pages(1));
            if ($config->entity->pages_in_pdf == 2) {
                $merger->addFile($path, new Pages(2));
            }
        }

        $now = Carbon::now();
        $storagePath = "files/".$now->year."/".$now->month."/".$now->day.'/';
        $fileName = $storagePath.$config->entityType."_".$config->entity->id."_A3_TEMP.pdf";
        $res = VTFiles::saveA3($merger->merge(), $fileName);
        if (!$res) {
            return back()->withErrors(['Could not create A3 PDF TEMP']);
        }
        $storageFileName = "app/".$fileName;
        $storageOutputFileName = "app/".$storagePath.$config->entityType."_".$config->entity->id."_A3_FINAL.pdf";
        $outputFileName = $storagePath.$config->entityType."_".$config->entity->id."_A3_FINAL.pdf";

        $c = "pdfnup --a3paper --suffix 2up ".storage_path($storageFileName)." --outfile ".storage_path($storageOutputFileName);

        exec($c);

        if (!file_exists(storage_path($storageOutputFileName))) {
            return back()->withErrors(['Could not create A3 PDF']);
        }

        $response = \Response::make(file_get_contents(storage_path($storageOutputFileName)), 200);
        $response->header('Content-Type', 'application/pdf');
        $response->header('Content-Disposition', 'filename="'.$config->entity->name.'.pdf"');

        Storage::disk('local')->delete($fileName);
        Storage::disk('local')->delete($outputFileName);

        return $response;
    }

    public static function createPdf($entityId, $entityType = null, $force = false, $a3 = false)
    {
        $config = new VTConfig($entityId, $entityType);

        // if it's a PDF, just return it straight away.
        if ($config->entityType == "VTRAM" && $config->entity->vtram_is_file) {
            $file = EGFiles::findOrFail($config->entity->vtram_file);
            $response = \Response::make(file_get_contents(storage_path()."/app/".$file->location), 200);
            $response->header('Content-Type', 'application/pdf');
            $response->header('Content-Disposition', 'filename="'.$file->filename.'.pdf"');
            return $response;
        }
        $company = Company::findOrFail($config->entity->company_id);
        if ($config->entity->status == 'PREVIOUS' || ($config->entity->pdf != null && !$force)) {
            return EGFiles::image($config->entity->pdf);
        }

        $logo = null;
        if ($config->entity->logo !== null) {
            $logo = $config->entity->logo;
        } else if ($config->entity->company_logo_id != null && $config->entity->companyLogo->logo != null) {
            $logo = $config->entity->companyLogo->logo;
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
                0 => $company->low_risk_character,
                1 => $company->med_risk_character,
                2 => $company->high_risk_character,
            ];
        } else {
            $riskList = [
                0 => 'L',
                1 => 'M',
                2 => 'H',
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
        $height = 120;

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
            'company' => $company,
            'a3' => $a3
        ];

        $html = view('pdf.main_report', $data)->render();
        // save html file
        $now = Carbon::now();
        $storagePath = "files/".$now->year."/".$now->month."/".$now->day.'/';
        $htmlFileName = $storagePath.$config->entityType."_".$config->entity->id."_HTML_TEMP.html";
        $htmlFile = VTFiles::saveA3($html, $htmlFileName);
        if ($htmlFile == null) {
            toast()->error("Failed to save PDF");
        } else {
            $pdfFileName = $storagePath.$config->entityType."_".$config->entity->id."_PDF_TEMP.pdf";
            $c = "google-chrome --headless --disable-gpu --print-to-pdf=".storage_path("app/".$pdfFileName)." --no-margins --no-footer ".storage_path("app/".$htmlFileName);
            //$c = "chromium-browser --no-sandbox --headless --disable-gpu --print-to-pdf=".storage_path("app/".$pdfFileName)." --no-margins --no-footer ".storage_path("app/".$htmlFileName);
            exec($c);
            chmod(storage_path("app/".$pdfFileName), 0777);

            $pdfContent = Storage::disk('local')->get($pdfFileName);
            if ($pdfContent === false) {
                toast()->error("Failed to save PDF");
            } else {
                $instances = null;
                preg_match_all('/Count [0-9]+/', $pdfContent, $instances);
                $count = 0;
                foreach ($instances[0] as $match) {
                    $m = (int)str_replace('Count ', '', $match);
                    if ($m > $count) {
                        $count = $m;
                    }
                }
                $file = VTFiles::saveOrUpdate($pdfContent, $config->entity, $config->entityType);
                if ($file == null) {
                    toast()->error("Failed to save PDF");
                } else {
                    $res = $config->entity->update([
                        'pdf' => $file->id,
                        'pages_in_pdf' => $count,
                    ]);
                    Storage::disk('local')->delete($htmlFileName);
                    Storage::disk('local')->delete($pdfFileName);
                }
                $response = \Response::make($pdfContent, 200);
                $response->header('Content-Type', 'application/pdf');
                $response->header('Content-Disposition', 'filename="'.$config->entity->name.'.pdf"');
                return $response;
            }
        }
    }

    public static function calculateStartingHeight($config)
    {
        $height = 0;
    }

    public static function submitForApproval($entityId, $entityType = null)
    {
        $config = new VTConfig($entityId, $entityType);
        self::createPdf($config->entity, null, true);
        if (in_array($config->entity->status, ['NEW', 'REJECTED','AMEND'])) {
            $newStatus = 'PENDING';
        } else if (in_array($config->entity->status, ['EXTERNAL_REJECT','EXTERNAL_AMEND']) && ($config->entityType == 'VTRAM' && $config->entity->project->principle_contractor)) {
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

        if (!is_null($entityType) && in_array($status, ['REJECTED', 'EXTERNAL_REJECT', 'CURRENT', 'PREVIOUS','AMEND','EXTERNAL_AMEND'])) {
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
        if ($entityType == 'COMPANY') {
            if ($config->entity->id == $user->company_id) {
                return true;
            }
        } else if ($entityType == 'TEMPLATE') {
            if ($config->entity->company_id == $user->company_id) {
                return true;
            }
        } else {
            if (in_array($config->entity->company_id, $user->getContractorIds())) {
                return true;
            }
        }

        return false;
    }

    public static function createDefaultMethodologies($entityId, $entityType)
    {
        $config = new VTConfig($entityId, $entityType);
        $methodologies = $config->entity->company->methodologies ?? null;
        if ($methodologies != null) {
            foreach ($methodologies as $meth) {
                $newMeth = $meth->replicate();
                $newMeth->entity = $config->entityType;
                $newMeth->entity_id = $config->entityId;
                $newMeth->save();

                foreach ($meth->icons as $icon) {
                    $newIcon = $icon->replicate();
                    $newIcon->methodology_id = $newMeth->id;
                    $newIcon->save();
                }

                foreach ($meth->instructions as $instruction) {
                    $newInstruction = $instruction->replicate();
                    $newInstruction->methodology_id = $newMeth->id;
                    $newInstruction->save();
                }

                foreach ($meth->tableRows as $row) {
                    $newRow = $row->replicate();
                    $newRow->methodology_id = $newMeth->id;
                    $newRow->save();
                }
            }
        }
    }

    public static function canReview($entityId, $entityType = null, $status = ['PENDING'])
    {
        $user = Auth::user();
        $isPrincipalContractor = false;
        if (is_null($user->company_id) || $user->company->is_principal_contractor) {
            $isPrincipalContractor = true;
            $status[] = 'AWAITING_EXTERNAL';
        }

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
        $replacements['{{page_break}}'] = '<div class="page"></div>';
        if (is_array($fields)) {
            foreach ($fields as $field) {
                $entity->{$field} = str_replace(array_keys($replacements), array_values($replacements), $entity->{$field});
            }
            return $entity;
        }
        return str_replace(array_keys($replacements), array_values($replacements), $entity->{$fields});
    }


    public static function saveAsTemplate($vtram, $templateToSupercede)
    {
        return DB::transaction(function () use ($vtram, $templateToSupercede) {

            $template = [];
            $template['company_id'] = $vtram['company_id'];
            $template['name'] = $vtram->name;
            $template['logo'] = $vtram->logo;
            $template['reference'] = $vtram->reference;
            $template['key_points'] = $vtram->key_points;
            $template['havs_noise_assessment'] = $vtram['havs_noise_assessment'];
            $template['coshh_assessment'] = $vtram['coshh_assessment'];
            $template['review_due'] = null;
            $template['approved_date'] = $vtram['approved_date'];
            $template['current_id'] = null;
            $template['status'] = "NEW";
            $template['created_by'] = Auth::User()->id;
            $template['submitted_by'] = null;
            $template['approved_by'] = null;
            $template['date_replaced'] = null;
            $template['resubmit_by'] = null;
            $template['show_area'] = $vtram['show_area'];
            $template['area'] = $vtram['area'];
            $template['main_description'] = $vtram['main_description'];
            $template['post_risk_assessment_text'] = $vtram['post_risk_assessment_text'];
            $template['pages_in_pdf'] = $vtram['pages_in_pdf'];
            $template['pdf'] = $vtram['pdf'];

            $template['created_from_entity'] = "VTRAM";
            $template['created_from_id'] = $vtram->id;
            $template['created_from'] = $vtram->id;

            $newTemplate = Template::create($template);

            if ($templateToSupercede) {
                $templateToSupercede->update([
                    'current_id' => $newTemplate->id,
                    'status' => 'PREVIOUS'
                ]);
            }

            $hazardOld = [];
            foreach ($vtram->hazards as $hazard) {
                $newHazard = $hazard->toArray();
                unset($newHazard['id']);
                $newHazard['entity'] = 'TEMPLATE';
                $newHazard['entity_id'] = $newTemplate->id;
                $copy = Hazard::create($newHazard);
                $hazardOld[$hazard->id] = $copy->id;
            }

            $insertIcons = [];
            $insertTableRows = [];
            $insertInstructions = [];
            $insertHazMethLink = [];
            foreach ($vtram->methodologies as $methodology) {
                $newMeth = $methodology->toArray();
                unset($newMeth['id']);
                $newMeth['entity'] = 'TEMPLATE';
                $newMeth['entity_id'] = $newTemplate->id;
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
            return $newTemplate;
        });
    }
}
