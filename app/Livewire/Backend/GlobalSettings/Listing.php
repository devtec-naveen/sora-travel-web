<?php

namespace App\Livewire\Backend\GlobalSettings;

use App\Livewire\Backend\DataTable;
use App\Traits\Toast;
use App\Services\Backend\CmsService;

class Listing extends DataTable
{
    use Toast;

    protected CmsService $service;

    public $values = [];
    public $originalValues = [];

    public function boot(CmsService $service)
    {
        $this->service = $service;
    }

    public function mount()
    {
        $this->values = $this->service->getValues();
        $this->originalValues = $this->values;
    }

    public function saveAll()
    {
        $this->service->updateChangedSettings(
            $this->values,
            $this->originalValues
        );

        $this->originalValues = $this->values;

        $this->Toast('success', 'Settings updated successfully!');
    }

    public function render()
    {
        return view('livewire.backend.global-settings.listing', [
            'settingList' => $this->service->getGroupedSettings()
        ]);
    }
}