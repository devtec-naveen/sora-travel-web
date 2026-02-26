<?php

namespace App\Services\Common;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class FileService
{
    /**
     * Upload file to public folder inside a specific subfolder
     *
     * @param UploadedFile $file
     * @param string $folderName  // e.g. 'images', 'documents'
     * @param string|null $prefix // optional prefix for file name
     * @return string|false       // Returns relative file path or false on failure
     */
    public function upload(UploadedFile $file, string $folderName, ?string $prefix = null): string|false
    {
        try {
            // Ensure folder exists inside public
            $destinationPath = public_path($folderName);
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Generate unique file name: [prefix_]YmdHis_random.ext
            $timeStamp = now()->format('YmdHis'); // e.g. 20260226153045
            $randomStr = Str::random(5);           // random string for uniqueness
            $prefixPart = $prefix ? $prefix . '_' : '';
            $extension = $file->getClientOriginalExtension();
            $fileName = $prefixPart . $timeStamp . '_' . $randomStr . '.' . $extension;

            // Move file to public folder
            $file->move($destinationPath, $fileName);

            // Return relative path
            return $folderName . '/' . $fileName;
        } catch (\Exception $e) {
            Log::error('File Upload Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove file from public folder
     *
     * @param string $filePath  // relative path like 'images/file.jpg'
     * @return bool
     */
    public function remove(string $filePath): bool
    {
        try {
            $fullPath = public_path($filePath);
            if (File::exists($fullPath)) {
                return File::delete($fullPath);
            }
            return false;
        } catch (\Exception $e) {
            Log::error('File Delete Error: ' . $e->getMessage());
            return false;
        }
    }
}