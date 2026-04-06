<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Aws\S3\S3Client;

class DownloadChunkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    protected string $remotePath;
    protected int $start;
    protected int $end;
    protected string $tempFile;
    protected string $cachePrefix;
    protected int $chunkIndex;
    protected int $numChunks;

    public $timeout = 0;
    public $tries = 5;

    public function __construct(
        string $remotePath,
        int $start,
        int $end,
        string $tempFile,
        string $cachePrefix,
        int $chunkIndex = 0,
        int $numChunks = 1
    ) {
        $this->remotePath  = $remotePath;
        $this->start       = $start;
        $this->end         = $end;
        $this->tempFile    = $tempFile;
        $this->cachePrefix = $cachePrefix;
        $this->chunkIndex  = $chunkIndex;
        $this->numChunks   = $numChunks;
    }

    public function handle()
    {
        // ---- S3 CLIENT (Wasabi) ----
        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => env('WASABI_DEFAULT_REGION', 'us-east-1'),
            'endpoint' => env('WASABI_ENDPOINT', 'https://s3.wasabisys.com'),
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key'    => env('WASABI_ACCESS_KEY_ID'),
                'secret' => env('WASABI_SECRET_ACCESS_KEY'),
            ],
        ]);

        $range = "bytes={$this->start}-{$this->end}";

        // ---- Stream chunk to temp file ----
        $result = $s3->getObject([
            'Bucket' => env('WASABI_BUCKET'),
            'Key'    => $this->remotePath,
            'Range'  => $range,
        ]);

        $body = $result['Body'];

        $write = fopen($this->tempFile, 'w+b');
        if (!$write) {
            throw new \RuntimeException("Cannot open temp file {$this->tempFile}");
        }

        $totalWritten = 0;
        $expectedLength = $this->end - $this->start + 1;

        // ---- Stream & track progress ----
        while (!$body->eof()) {
            $chunk = $body->read(1024 * 1024); // 1 MB

            if ($chunk === '') break;

            $written = fwrite($write, $chunk);
            if ($written !== strlen($chunk)) {
                fclose($write);
                throw new \RuntimeException("Failed writing bytes to file");
            }

            $totalWritten += $written;

            // ------- Update download stats in cache -------
            Cache::increment($this->cachePrefix . '_downloaded', $written);

            $total      = Cache::get($this->cachePrefix . '_total', 1);
            $downloaded = Cache::get($this->cachePrefix . '_downloaded', 0);

            $percent = (int) floor(($downloaded / $total) * 100);

            Cache::put($this->cachePrefix . '_progress', $percent, now()->addHours(3));

            // Give 95% weight to chunk downloads
            Cache::put($this->cachePrefix . '_overall_progress', (int) floor($percent * 0.95));
        }

        fclose($write);

        // ---- Validate chunk size ----
        if ($totalWritten !== $expectedLength) {
            throw new \RuntimeException("Chunk incomplete: expected {$expectedLength}, got {$totalWritten}");
        }
    }
}
