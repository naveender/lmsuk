<?php
// app/Services/SettingsService.php

namespace App\Services;

use App\Models\BackupSetting;

class SettingsService
{
    public function getAll()
    {
        return BackupSetting::all()->groupBy('group');
    }

    public function update(array $data)
    {
        foreach ($data as $key => $value) {
            BackupSetting::set($key, $value, $this->detectGroup($key));
        }
    }

    private function detectGroup($key)
    {
        return explode('.', $key)[0]; // backup.frequency → backup
    }
}
