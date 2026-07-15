<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
class FileHelper
{
    public static function getUniqueFilename(string $filename, string $disk = 'public'): string
    {
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $ext  = pathinfo($filename, PATHINFO_EXTENSION);

        $newFilename = $filename;
        $i = 1;

        while (Storage::disk($disk)->exists($newFilename)) {
            $newFilename = $name . '_' . $i . '.' . $ext;
            $i++;
        }

        return $newFilename;
    }
}
