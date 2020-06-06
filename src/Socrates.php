<?php

namespace Socrates;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use View;
use Config;
use Request;
use Session;
use ReflectionClass;

class Socrates
{
    protected $version;
    protected $filesystem;

    protected $actions = [
        
    ];

    protected $models = [
        
    ];

    public $setting_cache = null;

    /**
     * The current locale, cached in memory
     *
     * @var string
     */
    private $locale;

    public function __construct()
    {
        $this->filesystem = app(Filesystem::class);

        $this->findVersion();
    }

    public function getVersion()
    {
        return $this->version;
    }

    protected function findVersion()
    {
        if (!is_null($this->version)) {
            return;
        }

        if ($this->filesystem->exists(base_path('composer.lock'))) {
            // Get the composer.lock file
            $file = json_decode(
                $this->filesystem->get(base_path('composer.lock'))
            );

            // Loop through all the packages and get the version of facilitador
            foreach ($file->packages as $package) {
                if ($package->name == 'privatejustice/socrates') {
                    $this->version = $package->version;
                    break;
                }
            }
        }
    }
}
