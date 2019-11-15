<?php

namespace Gecche\Cupparis\Filesystem;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;

class FilesystemServiceProvider extends ServiceProvider
{


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

        Filesystem::macro('deleteFiles', function ($pattern,$flags = 0) {
            File::delete(File::glob($pattern,$flags));
        });

        Filesystem::macro('mimeFromGuesser', function ($path) {
            $guesser = MimeTypeGuesser::getInstance();

            try {
                $mimetype = $guesser->guess($path);
            } catch (\Exception $e) {
                return false;
            }

            return $mimetype;
        });

        Filesystem::macro('getIconaMime', function ($path, $iconeMimesArray = [], $default = 'default.png') {

            $mimetype = static::mimeFromGuesser($path);
            if ($mimetype === false) {
                return $default;
            }
            if (is_array($mimetype)) {
                $mimetype = current($mimetype);
            }

            return Arr::get($iconeMimesArray, $mimetype, $default);

        });


    }

}
