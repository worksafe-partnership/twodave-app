<?php

namespace App\Http\Classes;

use EGFiles;
use Carbon;
use Storage;

class VTFiles extends EGFiles
{
    public static function saveOrUpdate($fileContent, $entity, $entityType)
    {
        $now = Carbon::now();
        $storagePath = "files/".$now->year."/".$now->month."/".$now->day.'/'.$entityType.'_'.$entity->id.'.pdf';
        if (Storage::disk('local')->put($storagePath, $fileContent)) {
            $data = [
                'title' => $entity->name.'.pdf',
                'filename' => $entity->name.'.pdf',
                'location' => $storagePath,
                'mime_type' => 'application/pdf',
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $file = self::find($entity->pdf);
            if ($file == null) {
                return self::create($data);
            } else {
                $file->update($data);
                return $file;
            }
        }
        return false;
    }
    public static function saveNew($fileContent, $entity, $entityType, $fileName)
    {
        $now = Carbon::now();
        $storagePath = "files/".$now->year."/".$now->month."/".$now->day.'/'.$fileName;
        if (Storage::disk('local')->put($storagePath, $fileContent)) {
            $data = [
                'title' => $fileName,
                'filename' => $fileName,
                'location' => $storagePath,
                'mime_type' => '',
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $file = self::find($entity->pdf);
            if ($file == null) {
                return self::create($data);
            } else {
                $file->update($data);
                return $file;
            }
        }
        return false;
    }

    public static function saveA3($fileContent, $fileName)
    {
        if (Storage::disk('local')->put($fileName, $fileContent)) {
            return true;
        }
        return false;
    }

    public static function deleteFile($fileId, $perm = false)
    {
        self::findOrFail($fileId)->delete();
        if ($perm) {
            self::withTrashed()->findOrFail($fileId)->delete();
        }
    }
}
