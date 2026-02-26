<?php

namespace App\Services\Common;

class ChangeStatusService
{
    public function toggleStatus(string $model, int $id, string $field = 'status'): bool    {
        $record = $model::findOrFail($id);
        $record->{$field} = $record->{$field} === 'active' ? 'inactive' : 'active';
        $record->save();
        return true;
    }
}