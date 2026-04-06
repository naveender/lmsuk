<?php
//FILE: app/Jobs/DownloadBackupJob.php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use App\Models\RestoreLog;

class DownloadBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public string $filePath;
    public int $chunkSize = 500 * 1024 * 1024; // 500 MB per chunk
    public $timeout = 0;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        $log = RestoreLog::create([
            'file_name' => $this->filePath,
            'status' => 'processing',
            'type' => 'automatic',
            'action' => 'restore',
            'steps' => ['Job started'],
        ]);

        try {
            logger('Job is running…');
            $disk = Storage::disk('wasabi');
            $basename = basename($this->filePath);

            $localRelative = 'restores/' . uniqid('restore_') . '_' . $basename;
            $localFull = storage_path('app/' . $localRelative);

            if (!is_dir(dirname($localFull))) {
                mkdir(dirname($localFull), 0755, true);
            }

            $totalBytes = $disk->size($this->filePath);
            $numChunks = (int) ceil($totalBytes / $this->chunkSize);

            // Use batchId from trait if available
            $batchId = property_exists($this, 'batchId') ? $this->batchId : null;
            $cachePrefix = $batchId ? 'restore_' . $batchId : 'restore_' . md5($this->filePath);

            Cache::put($cachePrefix . '_total', $totalBytes, now()->addHours(3));
            Cache::put($cachePrefix . '_downloaded', 0, now()->addHours(3));
            Cache::put($cachePrefix . '_path', $localRelative, now()->addHours(3));
            Cache::put($cachePrefix . '_status', 'downloading', now()->addHours(3));
            Cache::put($cachePrefix . '_num_chunks', $numChunks, now()->addHours(3));
            Cache::put($cachePrefix . '_merge_progress', 0, now()->addHours(3));

            $jobs = [];
            $log->addStep('Downloading from Wasabi');
            for ($i = 0; $i < $numChunks; $i++) {
                $start = $i * $this->chunkSize;
                $end = min($totalBytes - 1, $start + $this->chunkSize - 1);
                $tempFile = storage_path("app/restores/tmp_chunk_{$i}.part");

                $jobs[] = new DownloadChunkJob($this->filePath, $start, $end, $tempFile, $cachePrefix, $i, $numChunks);
            }

            // Dispatch chunk jobs as a batch
            Bus::batch($jobs)
                ->then(function () use ($numChunks, $localFull, $cachePrefix, $log) {
                    // Merge all chunk files safely
                    
                    $write = fopen($localFull, 'w+b');
                    for ($i = 0; $i < $numChunks; $i++) {
                        $chunkFile = storage_path("app/restores/tmp_chunk_{$i}.part");
                        if (!file_exists($chunkFile)) {
                            throw new \RuntimeException("Missing chunk file: tmp_chunk_{$i}.part");
                        }
                        $read = fopen($chunkFile, 'rb');
                        stream_copy_to_stream($read, $write);
                        fclose($read);
                        @unlink($chunkFile);

                        // Update merge progress
                        $mergePercent = (int) floor((($i + 1) / $numChunks) * 100);
                        Cache::put($cachePrefix . '_merge_progress', $mergePercent, now()->addHours(3));
                        // Also update overall progress (chunk download + merge)
                        $downloadPercent = Cache::get($cachePrefix . '_progress', 0);
                        $overallPercent = (int) floor(($downloadPercent * 0.95) + ($mergePercent * 0.05));
                        Cache::put($cachePrefix . '_overall_progress', $overallPercent, now()->addHours(3));
                    }
                    $log->addStep('Merged all chunks to a single file');
                    fclose($write);

                    // Update status to "transferring"
                    Cache::put($cachePrefix . '_status', 'transferring', now()->addHours(3));
                    Cache::put($cachePrefix . '_progress', 95, now()->addHours(3));
                    Cache::put($cachePrefix . '_overall_progress', 95, now()->addHours(3));
                    logger('Copying files to server please wait…');
                    // Transfer the file to the remote server
                $scpCommand = [
                    'scp',
                    '-v',
                    '-i', '/home/backuprecovery/.ssh/id_rsa_give_permission_to_subdomain_no_pass',
                    '-o', 'StrictHostKeyChecking=no',
                    $localFull,
                    'mockvitaltrendsu@162.215.129.79:/home/mockvitaltrendsu/public_html/'
                ];
                $process = new Process($scpCommand);
                $process->setTimeout(0);      // allow unlimited time
                $process->setIdleTimeout(0);  // no idle timeout
                $process->run();
                if (!$process->isSuccessful()) {
                    logger('Copying files to server got failed…');
                    throw new \RuntimeException("File transfer failed: " . $process->getErrorOutput());
                }
                    logger('Successfully transfered filed…');
                    $log->addStep('Moving to server');
                    // Update status to "extracting"
                    Cache::put($cachePrefix . '_status', 'extracting', now()->addHours(3));
                    Cache::put($cachePrefix . '_progress', 98, now()->addHours(3));
                    Cache::put($cachePrefix . '_overall_progress', 98, now()->addHours(3));

                    logger('Localfull pathfile: '.$localFull);
                    $zipName = basename($localFull);
                    $remoteUser = 'mockvitaltrendsu';
                    $remoteHost = '162.215.129.79';
                    $remotePath = "/home/$remoteUser/public_html";
                    $folderName = pathinfo($zipName, PATHINFO_FILENAME);   // folder = zipname_without_extension
                    $extractFolder = "$remotePath/$folderName";
                    
                    logger('Initialized SSH cli…');

$remoteCommands = <<<EOF
mkdir -p "$extractFolder";

unzip -o "$remotePath/$zipName" -d "$extractFolder";

rm -f "$remotePath/$zipName";

mv "$extractFolder/public_html/"* "$remotePath/" 2>/dev/null || true;

SQL_FILE=\$(find "$extractFolder" -maxdepth 1 -name "*.sql" | head -n 1);

echo "SQL File found: \$SQL_FILE";

if [ -f "\$SQL_FILE" ]; then
    echo "Dropping all tables...";
    mysql -u mockvitaltrendsu_mock -p'V!hWfSX6Hz.$' mockvitaltrendsu_mock -e "
        SET FOREIGN_KEY_CHECKS = 0;
        SET @tables = (SELECT GROUP_CONCAT(table_name)
                       FROM information_schema.tables
                       WHERE table_schema = 'mockvitaltrendsu_mock');
        SET @stmt = CONCAT('DROP TABLE IF EXISTS ', @tables);
        PREPARE stmt FROM @stmt;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    ";

    echo "Importing SQL...";
    mysql -u mockvitaltrendsu_mock -p'V!hWfSX6Hz.$' mockvitaltrendsu_mock < "\$SQL_FILE";
else
    echo "No SQL file found in $extractFolder";
fi

rm -rf "$extractFolder";
EOF;

// REMOVE Windows CRLF
$remoteCommands = str_replace("\r", "", $remoteCommands);

// DO NOT use escapeshellarg here

$sshCommand = "ssh -i /home/backuprecovery/.ssh/id_rsa_give_permission_to_subdomain_no_pass -o StrictHostKeyChecking=no $remoteUser@$remoteHost " . escapeshellarg($remoteCommands);

$process = new Process(["bash", "-c", $sshCommand]);
$process->setTimeout(0);
$process->setIdleTimeout(0);
$process->run();



               

                    if (!$process->isSuccessful()) {
                        throw new \RuntimeException("File extraction failed: " . $process->getErrorOutput());
                    }
                logger("Output of ssh ");
                    // Update status to "completed"
                    Cache::put($cachePrefix . '_status', 'completed', now()->addHours(3));
                    Cache::put($cachePrefix . '_progress', 100, now()->addHours(3));
                    Cache::put($cachePrefix . '_overall_progress', 100, now()->addHours(3));
                    $log->addStep('Successfully deployed');
                    $log->update(['status' => 'completed']);
                })
                ->catch(function ($batch, \Throwable $e) use ($cachePrefix, $log) {
                    Cache::put($cachePrefix . '_status', 'failed', now()->addHours(3));
                    $log->addStep('Failed during batch processing: ' . $e->getMessage());
                    $log->update(['status' => 'failed']);
                })
                ->dispatch();
                // Disconnect from the database to free up connections
            DB::disconnect();
        } catch (\Throwable $e) {
            $log->addStep('Failed: ' . $e->getMessage());
            $log->update(['status' => 'failed']);
            throw $e;
        }
    }
}
