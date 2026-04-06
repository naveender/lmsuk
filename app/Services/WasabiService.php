<?php

namespace App\Services;

use Aws\S3\S3Client;

class WasabiService
{
    protected $s3;
    protected $bucket;

    public function __construct()
    {
        $this->bucket = env('WASABI_BUCKET');

        $this->s3 = new S3Client([
            'version'     => 'latest',
            'region'      => env('WASABI_DEFAULT_REGION'),
            'endpoint'    => env('WASABI_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key'    => env('WASABI_ACCESS_KEY_ID'),
                'secret' => env('WASABI_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    public function getBackupStats($prefix = "")
    {
        $totalFiles = 0;
        $totalSize = 0;

        $objects = $this->s3->getPaginator('ListObjectsV2', [
            'Bucket' => $this->bucket,
            'Prefix' => $prefix,
        ]);

        foreach ($objects as $page) {
            if (!empty($page['Contents'])) {
                foreach ($page['Contents'] as $file) {
                    $totalFiles++;
                    $totalSize += $file['Size'];
                }
            }
        }

        return [
            'total_backups'      => $totalFiles,
            'storage_used_gb'    => round($totalSize / (1024 ** 3), 2),
        ];
    }
}
