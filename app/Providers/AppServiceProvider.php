<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Schema::defaultStringLength(191);

        // Load dynamic configuration from settings database if table exists
        try {
            if (app()->bound('db') && Schema::hasTable('aspire_settings')) {
                // Load SMTP configs
                if ($smtpHost = setting('smtp.host')) {
                    config(['mail.mailers.smtp.host' => $smtpHost]);
                }
                if ($smtpPort = setting('smtp.port')) {
                    config(['mail.mailers.smtp.port' => $smtpPort]);
                }
                if ($smtpUser = setting('smtp.username')) {
                    config(['mail.mailers.smtp.username' => $smtpUser]);
                }
                if ($smtpPass = setting('smtp.password')) {
                    config(['mail.mailers.smtp.password' => $smtpPass]);
                }
                if ($smtpEnc = setting('smtp.encryption')) {
                    config(['mail.mailers.smtp.encryption' => $smtpEnc]);
                    config(['mail.mailers.smtp.scheme' => $smtpEnc]);
                }
                if ($smtpFrom = setting('smtp.from_address')) {
                    config(['mail.from.address' => $smtpFrom]);
                }
                if ($smtpFromName = setting('smtp.from_name')) {
                    config(['mail.from.name' => $smtpFromName]);
                }
                if ($smtpHost || $smtpUser) {
                    // Set SMTP as default mailer if host is configured
                    config(['mail.default' => 'smtp']);
                }

                // Load Wasabi configs
                if ($wasabiKey = setting('wasabi.key')) {
                    config(['filesystems.disks.wasabi.key' => $wasabiKey]);
                }
                if ($wasabiSecret = setting('wasabi.secret')) {
                    config(['filesystems.disks.wasabi.secret' => $wasabiSecret]);
                }
                if ($wasabiRegion = setting('wasabi.region')) {
                    config(['filesystems.disks.wasabi.region' => $wasabiRegion]);
                }
                if ($wasabiBucket = setting('wasabi.bucket')) {
                    config(['filesystems.disks.wasabi.bucket' => $wasabiBucket]);
                }
                if ($wasabiEndpoint = setting('wasabi.endpoint')) {
                    config(['filesystems.disks.wasabi.endpoint' => $wasabiEndpoint]);
                }
                config(['filesystems.disks.wasabi.http.verify' => false]);

                // Load S3 configs
                if ($s3Key = setting('s3.key')) {
                    config(['filesystems.disks.s3.key' => $s3Key]);
                }
                if ($s3Secret = setting('s3.secret')) {
                    config(['filesystems.disks.s3.secret' => $s3Secret]);
                }
                if ($s3Region = setting('s3.region')) {
                    config(['filesystems.disks.s3.region' => $s3Region]);
                }
                if ($s3Bucket = setting('s3.bucket')) {
                    config(['filesystems.disks.s3.bucket' => $s3Bucket]);
                }
            }

            // Share active subjects dynamically with horizontalbar partial
            if (app()->bound('db') && Schema::hasTable('subjects')) {
                view()->composer('partials.horizontalbar', function ($view) {
                    $activeSubjects = \App\Models\Subject::where('is_active', true)->get();
                    $view->with('navSubjects', $activeSubjects);
                });
            }
        } catch (\Exception $e) {
            // Prevent app crash during migrations or early boot
            logger()->error('Failed to load dynamic configurations: ' . $e->getMessage());
        }
    }
}
