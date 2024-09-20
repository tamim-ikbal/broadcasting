<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    public static function uploadFile($file, $oldFileUrl = null): string
    {

        //Generate Dynamic Path
        $dynamicPath = 'upload/'.date('Y').'/'.date('m');

        //Get Disk
        $disk = Storage::disk('public');

        //Create Directory if Not Exists
        if ( ! $disk->exists($dynamicPath)) {
            $disk->makeDirectory($dynamicPath);
        }

        //IF old file present then delete old file to free the disk
        if ($oldFileUrl) {
            $oldFilePath = str_replace(config('app.url').'/storage/', '', $oldFileUrl);
            $disk->delete($oldFilePath);
        }

        //Generate File url/path
        $filePath = $dynamicPath.'/'.$file->hashName();

        //Put File
        $disk->put($filePath, $file->getContent());

        //Generate File Public Url And Return
        return $filePath;
    }

    public static function deleteFile($thumbnail_url): void
    {
        //Get Disk
        $disk = Storage::disk('public');

        $oldFilePath = str_replace(config('app.url').'/storage/', '', $thumbnail_url);

        $disk->delete($oldFilePath);
    }

}
