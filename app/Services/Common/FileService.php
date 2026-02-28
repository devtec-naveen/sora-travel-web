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
    public function upload(\Illuminate\Http\UploadedFile $file, string $folderName, ?string $prefix = null): string|false
    {
        try {
            $destinationPath = public_path('uploads/' . $folderName);
            if (!is_dir($destinationPath) && !mkdir($destinationPath, 0755, true) && !is_dir($destinationPath)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $destinationPath));
            }

            // Unique filename
            $timeStamp = now()->format('YmdHis');
            $randomStr = \Illuminate\Support\Str::random(5);
            $prefixPart = $prefix ? $prefix . '_' : '';
            $extension = $file->getClientOriginalExtension();
            $fileName = $prefixPart . $timeStamp . '_' . $randomStr . '.' . $extension;

            // Use copy + unlink instead of move() for Livewire temp files
            $fileTempPath = $file->getRealPath();
            $finalPath = $destinationPath . '/' . $fileName;
            if (!copy($fileTempPath, $finalPath)) {
                throw new \RuntimeException('Failed to copy file to destination');
            }

            // Optionally delete temp file (Livewire cleans it automatically)
            // unlink($fileTempPath);

            return $fileName;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('File Upload Error: ' . $e->getMessage());
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
            $fullPath = public_path('uploads/'.$filePath);
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
