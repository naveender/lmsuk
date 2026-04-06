<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BackupSetting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [

            // -------------------------
            // STORAGE CONFIGURATION
            // -------------------------
            [
                'key'   => 'storage_default_path',
                'value' => '/var/backups/production',
                'group' => 'storage',
            ],
            [
                'key'   => 'storage_used_gb',
                'value' => '156.7',
                'group' => 'storage',
            ],
            [
                'key'   => 'storage_total_gb',
                'value' => '500',
                'group' => 'storage',
            ],

            // -------------------------
            // NOTIFICATION SETTINGS
            // -------------------------
            [
                'key'   => 'notification_email_enabled',
                'value' => '1',
                'group' => 'notification',
            ],
            [
                'key'   => 'notification_email',
                'value' => 'admin@gmail.com',
                'group' => 'notification',
            ],

            // -------------------------
            // SYSTEM INFORMATION
            // -------------------------
            [
                'key'   => 'system_backup_status',
                'value' => 'running',
                'group' => 'system',
            ],
            [
                'key'   => 'system_last_backup',
                'value' => '2 hours ago (Success)',
                'group' => 'system',
            ],
            [
                'key'   => 'system_version',
                'value' => 'BackupManager v1.0',
                'group' => 'system',
            ],
            [
                'key'   => 'system_database_engine',
                'value' => 'MySQL 15.4',
                'group' => 'system',
            ],
        ];

        foreach ($settings as $setting) {
            BackupSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'group' => $setting['group']]
            );
        }
    }
}
