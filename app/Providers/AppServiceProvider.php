<?php

namespace App\Providers;

use App\Filesystem\SupabaseS3Adapter;
use Aws\S3\S3Client;
use Carbon\Carbon;
use Illuminate\Filesystem\FilesystemAdapter as LaravelFilesystemAdapter;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        Carbon::setLocale('id');

        Blade::directive('tanggal', function ($expression) {
            return "<?php echo \\Carbon\\Carbon::parse($expression)->isoFormat('D MMM Y'); ?>";
        });

        Blade::directive('inisial', function ($expression) {
            return "<?php echo \\App\\Helpers\\InitialHelper::getInitials($expression); ?>";
        });

        Storage::extend('supabase', function ($app, array $config) {

            $region = ! empty($config['region'])
                ? $config['region']
                : 'ap-southeast-1';

            $client = new S3Client([
                'version' => 'latest',
                'region' => $region,
                'endpoint' => $config['endpoint'],
                'use_path_style_endpoint' => true,
                'credentials' => [
                    'key' => $config['key'],
                    'secret' => $config['secret'],
                ],
            ]);

            $adapter = new SupabaseS3Adapter(
                $client,
                $config['bucket']
            );

            $filesystem = new Filesystem($adapter, $config);

            return new LaravelFilesystemAdapter(
                $filesystem,
                $adapter,
                $config
            );
        });
    }
}
