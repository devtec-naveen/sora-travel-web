<?php

namespace App\Services\Common;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteService
{
    protected FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function deleteRecord(string $modelClass, int $id): array
    {
        try {
            DB::beginTransaction();
            if (!class_exists($modelClass)) {
                throw new \Exception("Model class not found.");
            }
            $model = $modelClass::find($id);
            if (!$model) {
                throw new \Exception("Record not found.");
            }
            $model->delete();
            DB::commit();
            return [
                'status' => true,
                'message' => 'Record deleted successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete Error: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function deleteRecordWithFile(string $modelClass, int $id, string $fileColumn, string $folder): array
    {
        try {
            DB::beginTransaction();
            if (!class_exists($modelClass)) {
                throw new \Exception("Model class not found.");
            }
            $model = $modelClass::find($id);
            if (!$model) {
                throw new \Exception("Record not found.");
            }
            if (!empty($model->$fileColumn)) {
                $filePath = $folder . '/' . $model->$fileColumn;
                $this->fileService->remove($filePath);
            }
            $model->delete();
            DB::commit();
            return [
                'status' => true,
                'message' => 'Record & file deleted successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete With File Error: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
