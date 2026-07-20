<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BackupSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SystemConfigController extends Controller
{
    public function index()
    {
        $configs = [
            'smtp_host' => setting('smtp.host', ''),
            'smtp_port' => setting('smtp.port', '587'),
            'smtp_username' => setting('smtp.username', ''),
            'smtp_password' => setting('smtp.password', ''),
            'smtp_encryption' => setting('smtp.encryption', 'tls'),
            'smtp_from_address' => setting('smtp.from_address', ''),
            'smtp_from_name' => setting('smtp.from_name', config('app.name')),
            
            'wasabi_key' => setting('wasabi.key', ''),
            'wasabi_secret' => setting('wasabi.secret', ''),
            'wasabi_region' => setting('wasabi.region', 'us-east-1'),
            'wasabi_bucket' => setting('wasabi.bucket', ''),
            'wasabi_endpoint' => setting('wasabi.endpoint', 'https://s3.wasabisys.com'),

            's3_key' => setting('s3.key', ''),
            's3_secret' => setting('s3.secret', ''),
            's3_region' => setting('s3.region', 'us-east-1'),
            's3_bucket' => setting('s3.bucket', ''),

            'max_upload_size' => setting('general.max_upload_size', '1024'), // in MB, default 1GB
        ];

        return view('admin.system-configs.index', compact('configs'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            // map from wasabi_key to wasabi.key etc.
            $dbKey = str_replace('_', '.', $key);
            
            // For general.max_upload_size, map manually if needed
            if ($key === 'max_upload_size') {
                $dbKey = 'general.max_upload_size';
            }
            
            $group = explode('.', $dbKey)[0];
            BackupSetting::set($dbKey, $value, $group);
        }

        return redirect()->back()->with('success', 'Configurations updated successfully.');
    }

    public function testSmtp(Request $request)
    {
        $request->validate([
            'host' => 'required',
            'port' => 'required|numeric',
            'username' => 'nullable',
            'password' => 'nullable',
            'encryption' => 'nullable',
            'from_address' => 'required|email',
        ]);

        try {
            // Apply config dynamically for this run
            config([
                'mail.mailers.smtp.host' => $request->host,
                'mail.mailers.smtp.port' => $request->port,
                'mail.mailers.smtp.username' => $request->username,
                'mail.mailers.smtp.password' => $request->password,
                'mail.mailers.smtp.encryption' => $request->encryption,
                'mail.mailers.smtp.scheme' => $request->encryption,
                'mail.from.address' => $request->from_address,
                'mail.from.name' => $request->from_name ?? config('app.name'),
                'mail.default' => 'smtp',
            ]);

            // Try sending a test email
            Mail::mailer('smtp')->raw('This is a test email verifying your LMS system email configurations.', function ($message) use ($request) {
                $message->to($request->from_address)
                        ->subject('LMS SMTP Connection Test');
            });

            return response()->json([
                'success' => true,
                'message' => 'Connection test successful! A test email has been sent to ' . $request->from_address,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function testWasabi(Request $request)
    {
        $request->validate([
            'key' => 'required',
            'secret' => 'required',
            'region' => 'required',
            'bucket' => 'required',
            'endpoint' => 'required',
        ]);

        try {
            // Apply Wasabi config dynamically
            config([
                'filesystems.disks.wasabi_test' => [
                    'driver' => 's3',
                    'key' => $request->key,
                    'secret' => $request->secret,
                    'region' => $request->region,
                    'bucket' => $request->bucket,
                    'endpoint' => $request->endpoint,
                    'use_path_style_endpoint' => true,
                    'options' => [
                        'verify' => false,
                    ],
                ]
            ]);

            $disk = Storage::disk('wasabi_test');
            $testFilename = 'connection_test_' . time() . '.txt';

            // 1. Write file
            $disk->put($testFilename, 'Wasabi connection test successful.');

            // 2. Read file
            $content = $disk->get($testFilename);

            // 3. Delete file
            $disk->delete($testFilename);

            if ($content === 'Wasabi connection test successful.') {
                return response()->json([
                    'success' => true,
                    'message' => 'Connection test successful! Managed to write, read, and delete a test file in the bucket.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Test file read content mismatched.',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function testS3(Request $request)
    {
        $request->validate([
            'key' => 'required',
            'secret' => 'required',
            'region' => 'required',
            'bucket' => 'required',
        ]);

        try {
            // Apply S3 config dynamically
            config([
                'filesystems.disks.s3_test' => [
                    'driver' => 's3',
                    'key' => $request->key,
                    'secret' => $request->secret,
                    'region' => $request->region,
                    'bucket' => $request->bucket,
                ]
            ]);

            $disk = Storage::disk('s3_test');
            $testFilename = 'connection_test_' . time() . '.txt';

            // 1. Write file
            $disk->put($testFilename, 'S3 connection test successful.');

            // 2. Read file
            $content = $disk->get($testFilename);

            // 3. Delete file
            $disk->delete($testFilename);

            if ($content === 'S3 connection test successful.') {
                return response()->json([
                    'success' => true,
                    'message' => 'Connection test successful! Managed to write, read, and delete a test file in the bucket.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Test file read content mismatched.',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
