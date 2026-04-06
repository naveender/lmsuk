<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ManualBackupManagerController extends Controller
{
    public function index()
    {
        return view('manual-backup-manager');
    }

    public function runBackup(Request $request)
    {
        $backupType = $request->input('backupType');

        if (!in_array($backupType, ['database', 'warranty-pdfs'], true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid backup type',
            ], 422);
        }

        return response()->json([
            'status' => 'started',
            'stream_url' => route('backup.stream', ['type' => $backupType]),
        ]);
    }

    public function stream(Request $request): StreamedResponse
    {
        $type = $request->query('type');

        if (!in_array($type, ['database', 'warranty-pdfs'], true)) {
            return response()->stream(function () {
                echo "event: message\n";
                echo "data: Invalid backup type\n\n";
                echo "event: done\n";
                echo "data: finished\n\n";
                @ob_flush();
                flush();
            }, 200, [
                'Content-Type' => 'text/event-stream',
                'Cache-Control' => 'no-cache',
                'X-Accel-Buffering' => 'no',
            ]);
        }

        return response()->stream(function () use ($type) {
            // Disable buffering
            if (ob_get_level()) {
                while (ob_get_level() > 0) {
                    ob_end_flush();
                }
            }
            ini_set('output_buffering', 'off');
            ini_set('zlib.output_compression', '0');
            ini_set('implicit_flush', '1');
            @ob_implicit_flush(1);

            $send = function (string $msg, string $event = 'message') {
                echo "event: {$event}\n";
                echo "data: {$msg}\n\n";
                @ob_flush();
                flush();
            };

            $dbName            = config('custom.prod_db_name');
            $dbUsername        = config('custom.prod_db_username');
            $dbPassword        = config('custom.prod_db_password');
            $prodCpanelRoot    = config('custom.prod_cpanel_root');
            $remoteUser        = config('custom.prod_cpanel_user');
            $remoteHost        = config('custom.server_ip_address');
            $sshKeyPath        = '/home/backuprecovery/.ssh/id_rsa_give_permission_to_subdomain_no_pass';

            $timestamp         = date('Y-m-d_H-i-s');

            if ($type === 'database') {
                $tempBackupPath  = "/home/backuprecovery/recoveryBackupStorage/database/dbbackup_{$timestamp}.tmp.sql";
                $finalBackupPath = "/home/backuprecovery/recoveryBackupStorage/database/dbbackup_{$timestamp}.sql";

                $send("Starting backup for: {$type}");

                $cmd = sprintf(
                    'mysqldump --no-tablespaces --verbose -u%s -p%s %s 2>&1 > %s',
                    escapeshellarg($dbUsername),
                    // note: password cannot be escaped with arg, so keep as is if you must use -p
                    $dbPassword,
                    escapeshellarg($dbName),
                    escapeshellarg($tempBackupPath)
                );

                $process = Process::fromShellCommandline($cmd);
                $process->setTimeout(0);

                $process->run(function ($stdoutType, $buffer) use ($send) {
                    $lines = preg_split('/\r\n|\r|\n/', trim($buffer));
                    foreach ($lines as $line) {
                        if ($line !== '') {
                            $send($line);
                        }
                    }
                });

                if (!$process->isSuccessful()) {
                    if (isset($tempBackupPath) && file_exists($tempBackupPath)) {
                        @unlink($tempBackupPath);
                    }
                    $send('❌ Backup failed: ' . $process->getErrorOutput());
                } else {
                    if (file_exists($tempBackupPath)) {
                        rename($tempBackupPath, $finalBackupPath);
                        $send('✅ Database backup saved: ' . $finalBackupPath);

                        $filename    = basename($finalBackupPath);
                        $downloadUrl = route('backup.download', $filename);
                        $send('DOWNLOAD: ' . $downloadUrl);
                    } else {
                        $send('❌ Backup failed: no output file created.');
                    }
                }
            }

            if ($type === 'warranty-pdfs') {
                $send("Starting backup for: {$type}");

                $destinationFolder = "/home/backuprecovery/recoveryBackupStorage/pdfWarrantyFiles";
                $zipFile           = "{$destinationFolder}/uploads_backup_{$timestamp}.zip";

                // rsync from remote
                $send("Starting rsync for warranty PDFs...");

                $rsyncCmd = sprintf(
                    "rsync -avz -e 'ssh -i %s -o StrictHostKeyChecking=no -p 22' %s@%s:%s/public_html/uploads %s",
                    escapeshellarg($sshKeyPath),
                    escapeshellarg($remoteUser),
                    escapeshellarg($remoteHost),
                    escapeshellarg($prodCpanelRoot),
                    escapeshellarg($destinationFolder)
                );

                $rsync = Process::fromShellCommandline($rsyncCmd);
                $rsync->setTimeout(0);

                $rsync->run(function ($stdoutType, $buffer) use ($send) {
                    $lines = preg_split('/\r\n|\r|\n/', trim($buffer));
                    foreach ($lines as $line) {
                        if ($line !== '') {
                            $send($line);
                        }
                    }
                });

                if (!$rsync->isSuccessful()) {
                    $send('❌ Rsync failed: ' . $rsync->getErrorOutput());
                    $send('finished', 'done');
                    return;
                }

                $send('✅ Rsync completed. Creating ZIP using command-line...');

                // zip uploads folder
                $zipCmd = sprintf(
                    "cd %s && zip -r %s uploads",
                    escapeshellarg($destinationFolder),
                    escapeshellarg($zipFile)
                );

                $zipProcess = Process::fromShellCommandline($zipCmd);
                $zipProcess->setTimeout(0);

                $zipProcess->run(function ($stdoutType, $buffer) use ($send) {
                    $lines = preg_split('/\r\n|\r|\n/', trim($buffer));
                    foreach ($lines as $line) {
                        if ($line !== '') {
                            $send($line);
                        }
                    }
                });

                if (!$zipProcess->isSuccessful()) {
                    $send('❌ ZIP creation failed: ' . $zipProcess->getErrorOutput());
                } else {
                    $send('✅ ZIP created: ' . $zipFile);
                    $filename    = basename($zipFile);
                    $downloadUrl = route('backup.downloadZip', $filename);
                    $send('DOWNLOAD: ' . $downloadUrl);
                }
            }

            // signal completion
            $send('finished', 'done');
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    public function download(string $filename)
    {
        $filePath = "/home/backuprecovery/recoveryBackupStorage/database/{$filename}";

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath, $filename, [
            'Content-Type' => 'application/sql',
        ]);
    }

    public function downloadZip(string $filename)
    {
        $filePath = "/home/backuprecovery/recoveryBackupStorage/pdfWarrantyFiles/{$filename}";

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath, $filename, [
            'Content-Type' => 'application/zip',
        ]);
    }
}
